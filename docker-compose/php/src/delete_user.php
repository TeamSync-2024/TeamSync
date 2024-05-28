<?php
require_once 'auth_check.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simulate deletion by replacing mandatory fields with random words
    $_SESSION['username'] = 'DeletedUser';
    // You can add more fields to simulate deletion if needed
 
    // Redirect the user to the profile page
    header("Location: user_page.php");
    exit;
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
