<?php
require_once 'auth_check.php';
require_once 'config.php';
ob_start();
function displayError($message) {
    header("Location: error.php?error=" . urlencode($message));
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    displayError("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$task_id = null;
$list_id = null;
$errorMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['task_id'])) {
    $task_id = filter_input(INPUT_GET, 'task_id', FILTER_VALIDATE_INT);
    error_log("Received task_id: " . $task_id);

    if ($task_id) {
        // Retrieve list_id associated with the task
        $stmt = $conn->prepare("SELECT task_list_id FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->bind_result($list_id);
        $stmt->fetch();
        $stmt->close();

        error_log("Associated list_id: " . $list_id);

        if ($list_id) {
            // Check if the list belongs to the current user
            $stmt = $conn->prepare("SELECT COUNT(*) FROM task_lists WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $list_id, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            error_log("Count of matching lists: " . $count);

            if ($count <= 0) {
                $errorMessage = "You are not authorized to delete this task.";
                error_log($errorMessage);
                displayError($errorMessage);
            }
        } else {
            displayError("No list found associated with this task.");
        }
    } else {
        displayError("Invalid task ID.");
    }
}

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id']) && isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 1) {
        $task_id = filter_input(INPUT_POST, 'task_id', FILTER_VALIDATE_INT);
        if ($task_id) {
            // First, delete the task from task_assignments table
            $stmt = $conn->prepare("DELETE FROM task_assignments WHERE task_id = ?");
            $stmt->bind_param("i", $task_id);
            $stmt->execute();
            $stmt->close();
            
            // Then, delete the task from tasks table
            $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $task_id);
            $stmt->execute();
            $stmt->close();
            
            ob_end_clean();
            header("Location: ../public/lists.php");
            exit();
        } else {
            displayError("Invalid task ID.");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="voltmaister & marked-d">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TeamSync</title>
  <link rel="stylesheet" href="../assets/styles.css">
  <script src="../assets/script.js" defer></script>
</head>
<body>
    <div id="header_container"></div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div id="navigation_container"></div>
    <?php endif;?>

    <main class="vertical">

        <div id="header-container"></div>

        <div class="center">
            <h1>Διαγραφή Εργασίας</h1>
        </div>
        
        <?php if ($task_id && !$errorMessage): ?>
        <div class="vertical">
            <div class="center">
                <p>Είστε σίγουροι πως θέλετε να διαγράψετε την εργασία;</p> 
            </div>
            <div class="center">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task_id); ?>">
                    <input type="hidden" name="confirm_delete" value="1">
                    <button class="red" type="submit">Διαγραφή Εργασίας</button>
                </form>  
                <a href="../public/tasks.php"><button>Ακύρωση</button></a>   
            </div>
        </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
        <div class="vertical">
            <div class="center">
                <p><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
            <div class="center">
                <a href="../public/tasks.php"><button>Επιστροφή</button></a>
            </div>
        </div>
        <?php endif; ?>

    </main>
    <div id="footer_container"></div>
</body>
</html>
