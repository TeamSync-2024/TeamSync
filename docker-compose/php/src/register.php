<?php
/**
 * User Registration Script
 *
 * This script handles the user registration process, including input validation,
 * password hashing, and sending a SimplePush notification upon successful registration.
 *
 * @author Your Name <your.email@example.com>
 * @version 1.0
 */

require_once 'config.php';
require_once 'simplePushNotification.php';

/**
 * Connect to the database
 */
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    $errorMessage = "Connection failed: " . $conn->connect_error;
    error_log($errorMessage);
    displayError($errorMessage);
}

/**
 * Redirect to the error page with the provided error message.
 *
 * @param string $message The error message to display.
 */
function displayError($message) {
    header("Location: error.php?error=" . urlencode($message));
    exit;
}

/**
 * Handle the user registration process.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $simplepush_key = filter_input(INPUT_POST, 'simplepush_key', FILTER_SANITIZE_STRING);

    /**
     * Check if any required fields are empty.
     * If so, log the error and display an error message.
     */
    if (empty($first_name) || empty($last_name) || empty($username) || empty($password) || empty($email) || empty($simplepush_key)) {
        $errorMessage = "All fields are required.";
        error_log($errorMessage);
        displayError($errorMessage);
    }

    // Hash the password with a random salt
    $salt = bin2hex(random_bytes(11)); // Generate a random salt
    $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12, 'salt' => $salt]);

    // Prepare and bind parameters for the check query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username =? OR email =?");
    $stmt->bind_param("ss", $username, $email);

    if (!$stmt->execute()) {
        $errorMessage = "Error executing statement: " . $stmt->error;
        error_log($errorMessage);
        displayError($errorMessage);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        displayError("Error: Username or email already exists");
    } else {
        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, email, simplepush_key) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $username, $password, $email, $simplepush_key);

        if ($stmt->execute()) {
            // Send a SimplePush notification upon successful registration
            $notification = new simplePushNotification();
            $result = $notification->sendNotification($simplepush_key, "Registration Successful", "Welcome, $first_name Your registration was successful.");
            if (!$result) {
                error_log("Error sending SimplePush notification");
            }
            header("Location: ../public/login.html");
        } else {
            $errorMessage = "Error: " . $stmt->error;
            error_log($errorMessage);
            displayError($errorMessage);
        }
    }

    $stmt->close();
}

$conn->close();