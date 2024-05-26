<?php
// auth_checker.php

// Start the session
session_start();

require_once 'config.php';
// Define the required session variables
$required_session_vars = ['user_id', 'username'];

// Function to check if all required session variables are set
function allRequiredSessionsSet($sessions) {
    foreach ($sessions as $session) {
        if (!isset($_SESSION[$session])) {
            return false;
        }
    }
    return true;
}

// Check if all required session variables are set
if (!allRequiredSessionsSet($required_session_vars)) {
    // Check if a valid remember token is present
    if (isset($_COOKIE['remember_token'])) {
        $rememberToken = $_COOKIE['remember_token'];

        // Connect to the database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Prepare and execute the select statement
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE remember_token = ? AND remember_token_expiry > ?");
        $stmt->bind_param("si", $rememberToken, time());

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Regenerate session ID after restoring the session
            session_regenerate_id(true);
        }

        $stmt->close();
        $conn->close();
    }

    // If the session variables are still not set, redirect to the login page
    if (!allRequiredSessionsSet($required_session_vars)) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        header("Location:../public/login.html");
        exit;
    }
}

// Check if the session has expired
$sessionDuration = 3600; // 1 hour in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionDuration)) {
    // Session has expired
    session_unset();
    session_destroy();
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location:../public/login.html");
    exit;
}

// Update the last activity time
$_SESSION['last_activity'] = time();
?>