<?php

require_once '../src/auth_check.php';

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
        <h1>Δημιουργήστε μια νέα λίστα</h1>
    </div>
    
    <div class="center">
      <div class="max_width">
          <form action="../src/make_list.php" method="post">
              <label for="list_name"><b>Όνομα:</b></label><br>
              <input type="text" name="list_name" placeholder="Όνομα λίστας" ><br><br>

              <label for="list_description"><b>Περιγραφή:</b></label><br>
              <textarea name="list_description" cols="22" rows="3" placeholder="Περιγραφή λίστας" ></textarea><br><br>
              
              <div class="center">
                <button type="submit" value="Create List">Δημιουργία Λίστας</button>
              </div>
              
          </form>
      </div>
    </div>

    </main>
      <div id="footer_container"></div>
  </body>
</html>