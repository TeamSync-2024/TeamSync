<?php
require_once 'auth_check.php';
session_start();

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
    <title>Delete Profile</title>
</head>
<body>
    <h1>Delete Profile</h1>
    <p>Are you sure you want to delete your profile?</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" value="Delete Profile">
    </form>
    <p><a href="user_profile.php">Cancel</a></p>
</body>
</html>
