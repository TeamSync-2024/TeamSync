<?php
require_once 'auth_check.php';
require_once 'config.php';
require_once 'simplePushNotification.php';

/**
 * Redirect to the error page with the provided error message.
 *
 * @param string $message The error message to display.
 */
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
    $list_name = filter_input(INPUT_POST, 'list_name', FILTER_UNSAFE_RAW);
    $list_description = filter_input(INPUT_POST, 'list_description', FILTER_UNSAFE_RAW);
    $user_id = $_SESSION['user_id'];

    if (empty($list_name) || empty($list_description)) {
        $errorMessage = "All fields are required.";
        error_log($errorMessage);
        displayError($errorMessage);
    }

    $stmt = $conn->prepare("INSERT INTO task_lists (title, description, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $list_name, $list_description, $user_id);

    if (!$stmt->execute()) {
        $errorMessage = "Error executing statement: " . $stmt->error;
        error_log($errorMessage);
        displayError($errorMessage);
    }

    $list_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();

    header("Location: ../public/lists.php");
    exit;
}
?>