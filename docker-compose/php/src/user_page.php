<?php
require_once 'auth_check.php';
session_start();

// Display user information
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>This is your user profile page. You can view your details here.</p>
    <p><a href="edit_profile.php">Edit Profile</a></p>
    <form action="delete_user.php" method="post">
        <input type="submit" value="Delete Profile">
    </form>
    <p><a href="../public/logout.php">Logout</a></p>
</body>
</html>
