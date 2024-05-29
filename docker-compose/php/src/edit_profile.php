<?php
error_reporting(E_ALL & ~E_NOTICE);
ob_start();
session_start(); // Ensure session is started

require_once 'config.php'; // Include your config file
require_once 'auth_check.php'; // Include the auth check file
require_once '../src/simplePushNotification.php';

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
        <h1>Ενημέρωση Προφίλ</h1> 
    </div>

    <div class="center">
        <div class="max_width">
            <form action="" method="post">
                
                <label for="first_name"><b>Όνομα:</b></label><br>
                <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>"><br><br>

                <label for="last_name"><b>Επώνυμο:</b></label><br>
                <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>"><br><br>

                <label for="username"><b>Username:</b></label><br>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>"><br><br>

                <label for="password"><b>Κωδικός:</b></label><br>
                <input type="text" id="password" name="password" value=""><br><br>

                <label for="email"><b>Email:</b></label><br>
                <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>"><br><br>

                <label for="simplepush_key"><b>SimplePush.io Key:</b></label><br>
                <input type="text" id="simplepush_key" name="simplepush_key" value="<?php echo $user['simplepush_key']; ?>"><br><br>
                
                <div class="center">
                    <button type="submit" name="update">Ενημέρωση Προφίλ</button><br><br> 
                </div>

            </form>
            <div class="center">
                   <a href="../src/user_page.php"><button>Ακύρωση</button></a> 
            </div>
        </div>
    </div>

    </main>
  <div id="footer_container"></div>
</body>
</html>