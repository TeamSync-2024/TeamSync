<?php
session_start();

require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']) ? true : false;

    // Prepare the select statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");

    // Check if the statement preparation was successful
    if ($stmt === false) {
        // Handle the error, e.g., log or display an error message
        die("Error preparing the SQL statement: " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Regenerate session ID after successful authentication
            session_regenerate_id(true);

            // Handle Remember Me functionality
            if ($rememberMe) {
                $rememberToken = bin2hex(random_bytes(32));
                $expirationTime = time() + (86400 * 30); // 30 days

                // Update the user's remember_token and remember_token_expiry in the database
                $updateStmt = $conn->prepare("UPDATE users SET remember_token = ?, remember_token_expiry = ? WHERE id = ?");
                if ($updateStmt === false) {
                    die("Error preparing the update statement: " . $conn->error);
                }
                $updateStmt->bind_param("ssi", $rememberToken, $expirationTime, $row['id']);
                $updateStmt->execute();
                $updateStmt->close();

                setcookie('remember_token', $rememberToken, $expirationTime, '/');
            } else {
                // If the user didn't check the "Remember Me" checkbox, clear the remember token
                $updateStmt = $conn->prepare("UPDATE users SET remember_token = NULL, remember_token_expiry = NULL WHERE id = ?");
                if ($updateStmt === false) {
                    die("Error preparing the update statement: " . $conn->error);
                }
                $updateStmt->bind_param("i", $row['id']);
                $updateStmt->execute();
                $updateStmt->close();

                setcookie('remember_token', '', time() - 3600, '/'); // Delete the cookie
            }

            // Redirect to the appropriate page after successful login
            header("Location: ../index.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
            echo $error_message;
        }
    } else {
        $error_message = "Invalid username or password.";
        echo $error_message;
    }

    $stmt->close();
    $conn->close();
}
?>
