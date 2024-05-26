<?php
error_reporting(E_ALL & ~E_NOTICE);
ob_start();
session_start(); // Ensure session is started

require_once 'config.php'; // Include your config file
require_once 'auth_check.php'; // Include the auth check file

$userId = $_SESSION['user_id'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_UNSAFE_RAW);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_UNSAFE_RAW);
    $username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
    $new_password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW); // Get the new password without sanitizing it
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $simplepush_key = filter_input(INPUT_POST, 'simplepush_key', FILTER_UNSAFE_RAW);

    // Ensure $userId is defined and holds the correct user ID
    $userId = $_SESSION['user_id'];

    // If a new password is provided, use it; otherwise, use the existing password
    $password = $user['password']; // Default to the existing hashed password
    if (!empty($new_password)) {
        // Hash the new password using the built-in salting mechanism
        $password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    $sql = "UPDATE users SET first_name = ?, last_name = ?, username = ?, password = ?, email = ?, simplepush_key = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    } else {
        $stmt->bind_param("ssssssi", $first_name, $last_name, $username, $password, $email, $simplepush_key, $userId);

        if ($stmt->execute()) {
            ob_end_clean();
            header("Location: user_page.php");
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
    <title>Edit Profile</title>
</head>
<body>
<form action="" method="post">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>">

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>">

    <label for="password">Password:</label>
    <input type="text" id="password" name="password" value="">

    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>">

    <label for="simplepush_key">SimplePush Key:</label>
    <input type="text" id="simplepush_key" name="simplepush_key" value="<?php echo $user['simplepush_key']; ?>">

    <button type="submit" name="update">Update Profile</button>
</form>
    <a href="./user_page.php">
        <button>Cancel</button>
    </a>
</body>
</html>