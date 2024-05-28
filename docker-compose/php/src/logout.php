<?php

//logout.php
session_start();
session_unset();
session_destroy();
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
      <h1>Έχετε αποσυνδεθεί με επιτυχία</h1>
      <p>
        Κάνετε κλικ <a href="../public/login.html">εδώ</a> για να συνδεθείτε ξανά. <br><br>
        Επιστροφή στην <a href="frontPage.html">αρχική</a>.
      </p>
    </main>
    <div id="footer-container"></div>
</body>
</html>