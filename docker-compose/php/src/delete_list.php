<?php
require_once 'auth_check.php';
require_once 'config.php';

function displayError($message) {
    header("Location: error.php?error=" . urlencode($message));
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    $errorMessage = "Connection failed: " . $conn->connect_error;
    error_log($errorMessage);
    displayError($errorMessage);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $confirm_delete = filter_input(INPUT_POST, 'confirm_delete', FILTER_VALIDATE_INT);
    $list_id = filter_input(INPUT_GET, 'list_id', FILTER_VALIDATE_INT);
    $user_id = $_SESSION['user_id'];

    if ($confirm_delete === 1 && !empty($list_id)) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Delete tasks from task_assignments
            $stmt = $conn->prepare("DELETE FROM task_assignments WHERE task_id IN (SELECT id FROM tasks WHERE task_list_id = ?)");
            $stmt->bind_param("i", $list_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting task assignments: " . $stmt->error);
            }
            $stmt->close();

            // Delete tasks from tasks
            $stmt = $conn->prepare("DELETE FROM tasks WHERE task_list_id = ?");
            $stmt->bind_param("i", $list_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting tasks: " . $stmt->error);
            }
            $stmt->close();

            // Delete the list from task_lists
            $stmt = $conn->prepare("DELETE FROM task_lists WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $list_id, $user_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting task list: " . $stmt->error);
            }
            $stmt->close();

            // Commit the transaction
            $conn->commit();

            header("Location: ../public/lists.php");
            exit;
        } catch (Exception $e) {
            // Rollback the transaction if any error occurs
            $conn->rollback();
            $errorMessage = $e->getMessage();
            error_log($errorMessage);
            displayError($errorMessage);
        } finally {
            $conn->close();
        }
    } else {
        $errorMessage = "Invalid request.";
        error_log($errorMessage);
        displayError($errorMessage);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $list_id = filter_input(INPUT_GET, 'list_id', FILTER_VALIDATE_INT);

    if (empty($list_id)) {
        $errorMessage = "List ID is required.";
        error_log($errorMessage);
        displayError($errorMessage);
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
            <h1>Διαγραφή Λίστας</h1>
        </div>
        
        <div class="vertical">
            <div class="center">
                <p>Είστε σίγουροι πως θέλετε να διαγράψετε τη λίστα;</p> 
            </div>
            <div class="center">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?list_id=' . htmlspecialchars($list_id); ?>" method="post">
                    <input type="hidden" name="confirm_delete" value="1">
                    <button class="red" type="submit">Διαγραφή λίστας</button>
                </form>  
                <a href="../public/lists.php"><button>Ακύρωση</button></a>   
            </div>
        </div>

    </main>
    <div id="footer_container"></div>
</body>
</html>
