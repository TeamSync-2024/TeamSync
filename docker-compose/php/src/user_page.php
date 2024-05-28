<?php

require_once 'auth_check.php';

// Display user information
$username = $_SESSION['username'];
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
    <div id="header-container"></div>
    <main>
    <h1>Καλωσήρθατε, <?php echo $username; ?>!</h1>
    <p>Αυτή είναι η σελίδα του προφίλ σας. Μπορείτε να επεξεργαστειτε τα στοιχεια σας, να τα διαγραψετε ή να κανετε αποσυνδεση.</p>
    <p><a href="edit_profile.php">Edit Profile</a></p>
    <form action="delete_user.php" method="post">
        <input type="submit" value="Delete Profile">
    </form>
    <p><a href="../public/logout.php">Logout</a></p>
    </main>
    <div id="footer-container"></div>
</body>
</html>