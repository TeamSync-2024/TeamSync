<?php
error_reporting(E_ALL & ~E_NOTICE);
ob_start();
session_start(); // Ensure session is started
require_once 'config.php'; // Include your config file
require_once 'auth_check.php'; // Include the auth check file
require_once 'simplePushNotification.php'; // Adjust the path as necessary

$task_id = isset($_GET['task_id']) ? $_GET['task_id'] : null;

if (!$task_id) {
    header("Location: ../public/lists.php");
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $task_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
$task = $result->fetch_assoc();
$stmt->close();

// Fetch the assigned users for the current task
$stmt = $conn->prepare("SELECT u.username
                        FROM task_assignments ta
                        JOIN users u ON ta.user_id = u.id
                        WHERE ta.task_id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$assigned_users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = filter_input(INPUT_POST, 'task_description', FILTER_UNSAFE_RAW);
    $due_date = filter_input(INPUT_POST, 'task_due_date', FILTER_UNSAFE_RAW);
    $status = filter_input(INPUT_POST, 'task_status', FILTER_UNSAFE_RAW);
    $assigned_usernames = filter_input(INPUT_POST, 'assigned_usernames', FILTER_UNSAFE_RAW);

    // Delete all existing assignments for the task
    $stmt = $conn->prepare("DELETE FROM task_assignments WHERE task_id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();

    // Insert new assignments for the provided usernames
    $assigned_usernames = explode(",", $assigned_usernames); // Split the usernames by comma
    foreach ($assigned_usernames as $username) {
        $username = trim($username); // Remove leading/trailing spaces
        if (!empty($username)) {
            $stmt = $conn->prepare("INSERT INTO task_assignments (task_id, user_id)
                                    SELECT ?, id FROM users WHERE username = ?");
            $stmt->bind_param("is", $task_id, $username);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    $sql = "UPDATE tasks SET description = ?, due_date = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    } else {
        $stmt->bind_param("sssi", $description, $due_date, $status, $task_id);
        if ($stmt->execute()) {
            ob_end_clean();
            /* header("Location: ../public/tasks.php?list_id=" . $task['task_list_id']); */
            exit;
        } else {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $stmt->close();
        }
    }
}

$conn->close();
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
  <main class="vertical">
    <div class="center">
        <h1>Εργασία: <?php echo $task['title']; ?></b></h1>
    </div>

    <div class="center">
        <div class="max_width">
            <form action="" method="post">

                <label for="description"><b>Περιγραφή Εργασίας:</b></label><br>
                <textarea name="description" placeholder="Περιγραφή εργασίας" cols="35" rows="5" required><?php echo $task['description']; ?></textarea><br><br>
    
                <label for="status"><b>Κατάσταση: </b></label>
                <select name="task_status">
                    <option value="pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>Σε εκκρεμότητα</option>
                    <option value="in-progress" <?php echo $task['status'] === 'in-progress' ? 'selected' : ''; ?>>Σε εξέλιξη</option>
                    <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Ολοκληρώθηκε</option>
                </select><br><br>

                <label for="due_date"><b>Ημερομηνία Προθεσμίας:</b></label><br>
                <input type="date" name="task_due_date" value="<?php echo $task['due_date']; ?>" required><br><br>

                <label for="assigned_to"><b>Ανάθεση σε: </b><br>(Διαχωρίστε τους χρήστες με κόμμα)</label><br>
                <input type="text" name="assigned_usernames" value="<?php echo implode(", ", array_column($assigned_users, 'username')); ?>" placeholder="Διαχωρίστε τα ονόματα χρηστών με κόμμα"><br><br>

                <div class="center">
                    <button type="submit" value="Update Task">Ενημέρωση Εργασίας</button>
                </div>
            </form>
            <div class="center">
                <a href="../public/tasks.php?list_id=<?php echo $task['task_list_id']; ?>"><button>Ακύρωση</button></a>
            </div>
        </div>
    </div>

    </main>
  <div id="footer_container"></div>
</body>
</html>