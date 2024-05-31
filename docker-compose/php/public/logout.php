<?php

session_start();
if (isset($_COOKIE['remember_token'])) {
  unset($_COOKIE['remember_token']); 
  setcookie('remember_token', '', -1, '/'); 
}
session_unset();
session_destroy();

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
       <h1>Αποσύνδεση</h1> 
    </div>

    <div class="vertical">
      <div class="center">
        <p>Έχετε αποσυνδεθεί με επιτυχία.</p>
      </div>
      <div class="center">
        <a href="../public/login.html"><button>Συνδεθείτε ξανά</button></a>
      </div>
    </div>

  </main>
  <div id="footer_container"></div>
</body>
</html>