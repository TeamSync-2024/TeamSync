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
        header("Location: error.php?error=" . urlencode($errorMessage));
        exit();
    }

    // Get the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Prepare the update statement to replace mandatory fields with random words
    $stmt = $conn->prepare("UPDATE users SET username = ?, first_name = ?, last_name = ?, email = ?, simplepush_key = ? WHERE id = ?");
    $random_username = generateRandomString(10);
    $random_first_name = generateRandomString(8);
    $random_last_name = generateRandomString(8);
    $random_email = generateRandomString(10) . "@example.com";
    $new_simplepush_key = " ";
    $stmt->bind_param("sssssi", $random_username, $random_first_name, $random_last_name, $random_email, $new_simplepush_key, $user_id);

    if ($stmt->execute()) {
        // Update successful
        $_SESSION['username'] = $random_username; // Update the session with the new random username
        session_destroy(); // Destroy the user's session
        header("Location: ../public/logout.php");
        exit();
    } else {
        $errorMessage = "Error updating user data: " . $stmt->error;
        error_log($errorMessage);
        // Handle the error appropriately, e.g., display an error message
        header("Location: error.php?error=" . urlencode($errorMessage));
        exit();
    }

    $stmt->close();
    $conn->close();
}

// Function to generate a random string
function generateRandomString($length) {
    $bytes = random_bytes(ceil($length / 2));
    return substr(bin2hex($bytes), 0, $length);
}
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

    <?php if (isset($_SESSION['user_id'])): ?>
        <div id="navigation_container"></div>
    <?php endif;?>

    <main class="vertical">

        <div id="header-container"></div>

        <div class="center">
            <h1>Διαγραφή Προφίλ</h1>   
        </div>
        
        <div class="vertical">
            <div class="center">
                <p>Είστε σίγουροι πως θέλετε να διαγράψετε το προφίλ σας;</p> 
            </div>
            <div class="center">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="confirm_delete" value="1"> <!-- Added hidden input -->
                    <button class="red" type="submit">Διαγραφή Προφίλ</button> <!-- Corrected button type -->
                </form>  
                <a href="../src/user_page.php"><button>Ακύρωση</button></a>   
            </div>
            
        </div>

    </main>
  <div id="footer_container"></div>
</body>
</html>
