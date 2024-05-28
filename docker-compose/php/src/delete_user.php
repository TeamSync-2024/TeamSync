<?php
require_once 'auth_check.php';

// Check if the form was submitted with the 'confirm_delete' field
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    // Connect to the database
    require_once 'config.php';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        $errorMessage = "Connection failed: " . $conn->connect_error;
        error_log($errorMessage);
        // Handle the error appropriately, e.g., display an error message
    }

    // Get the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Prepare the update statement to replace mandatory fields with random words
    $stmt = $conn->prepare("UPDATE users SET username = ?, first_name = ?, last_name = ?, email = ?, simplepush_key= ? WHERE id = ?");
    $random_username = generateRandomString(10);
    $random_first_name = generateRandomString(8);
    $random_last_name = generateRandomString(8);
    $random_email = generateRandomString(10) . "@example.com";
    $simplepush = " ";
    $stmt->bind_param("sssssi", $random_username, $random_first_name, $random_last_name, $random_email, $simplepush, $user_id);

    if ($stmt->execute()) {
        // Update successful
        $_SESSION['username'] = $random_username; // Update the session with the new random username
        header("Location: ../public/logout.php");
        exit;
    } else {
        $errorMessage = "Error updating user data: " . $stmt->error;
        error_log($errorMessage);
        // Handle the error appropriately, e.g., display an error message
    }

    $stmt->close();
    $conn->close();
}

// Function to generate a random string
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TeamSync - Task Management Website</title>
  <link rel="stylesheet" href="styles.css">
  <script src="script.js" defer></script>
</head>
<body>
    <main>
        <div id="header-container"></div>
        <h1>Διαγραφή Προφίλ</h1>
        <p>Είστε σίγουροι πως θέλετε να διαγράψετε το προφίλ σας;</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <button type="submit" value="Delete Profile">Διαγραφή Προφίλ</button>
        </form>
        <p><a href="user_profile.php"><button>Ακύρωση</button></a></p>
    </main>
    <div id="footer-container"></div>
</body>
</html>