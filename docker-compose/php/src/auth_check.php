<?php
// auth_checker.php

// Start the session
session_start();

// Define the required session variables
$required_session_vars = ['user_id', 'username']; // Array of session variables to check

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
    // Redirect to the login page or an error page
    header("Location:../public/login.html"); // Adjust the path as necessary
    exit;
}
?>
