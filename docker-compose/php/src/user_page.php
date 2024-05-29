<?php

require_once 'auth_check.php';

// Display user information
$username = $_SESSION['username'];
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
      <h1>Καλωσήρθατε, <?php echo $username; ?>!</h1>
    </div>
    
    <div class="center">
      <p>
        Αυτή είναι η σελίδα του προφίλ σας. Μπορείτε να επεξεργαστείτε τα στοιχεία σας, να τα διαγράψετε ή να κάνετε αποσύνδεση.
      </p>
    </div>

    <div>
      <!-- Emfanish stoixeivn profil Orestis job -->
    </div>

    <div class="horizontal">

      <div class="max_width">
        <a href="../src/edit_profile.php"><button>Επεξεργασία Προφίλ</button></a>
      </div>
      <div class="max_width">
        <form action="delete_user.php" method="post">
            <button class="red" type="submit" value="Delete Profile">Διαγραφή Προφίλ</button>
        </form> 
      </div>
      <div class="max_width">
        <a href="../public/logout.php"><button class="orange">Αποσύνδεση</button></a>
      </div>

    </div>

    </main>
  <div id="footer_container"></div>
</body>
</html>