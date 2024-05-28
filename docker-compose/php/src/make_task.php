<?php
require_once 'auth_check.php'; // Adjust the path as necessary
require_once 'config.php'; // Adjust the path as necessary
require_once 'simplePushNotification.php'; // Adjust the path as necessary
ob_start();
function displayError($message) {
    header("Location: error.php?error=". urlencode($message));
    exit;
}
/* get user id from session */
$userId = $_SESSION['user_id'];

$list_id = isset($_GET['list_id'])? $_GET['list_id'] : null;
if (!$list_id) {
    displayError("List ID is missing.");
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    $errorMessage = "Connection failed: ". $conn->connect_error;
    error_log($errorMessage);
    displayError($errorMessage);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = filter_input(INPUT_POST, 'task_title', FILTER_UNSAFE_RAW);
    $task_description = filter_input(INPUT_POST, 'task_description', FILTER_UNSAFE_RAW);
    $task_due_date = filter_input(INPUT_POST, 'task_due_date', FILTER_UNSAFE_RAW);
    $list_id = filter_input(INPUT_POST, 'list_id', FILTER_SANITIZE_NUMBER_INT);
    $status = 'pending'; // Default status
    if (!$task_name || !$task_description || !$task_due_date || !$list_id) {
        displayError("All fields are required.");
    }

    // Assuming $task_due_date contains a date in the format 'YYYY-MM-DD'
    $task_due_date_formatted = date("Y-m-d", strtotime($task_due_date));

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert the task into the database
        $sql = "INSERT INTO tasks (title, description, due_date, task_list_id, status) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssis", $task_name, $task_description, $task_due_date_formatted, $list_id, $status);
        $stmt->execute();

        // Get the last inserted task ID
        $task_id = $stmt->insert_id;
        $stmt->close();

        // Insert the task assignment
        $sql = "INSERT INTO task_assignments (task_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $task_id, $userId);
        $stmt->execute();
        $stmt->close();

        // send notification to the user using the user_id to find the key for the simplePushNotification class
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $pushKey = $user['simplepush_key'];

        //now we will send the message with the class
        $push = new simplePushNotification();
        $push->sendNotification($pushKey, "New Task Assigned", "You have been assigned a new task: ".$task_name);   

        // Commit the transaction
        $conn->commit();
        ob_end_clean();
        header("Location: ../public/tasks.php?list_id=". $list_id);
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Error inserting task: " . $e->getMessage();
    }
} else {
    displayError("Invalid request method.");
}

$conn->close();
?>