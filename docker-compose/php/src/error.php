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
    
    <div class="center">
        <h1>Σφάλμα</h1> 
    </div>
    
    <div class="center">
        <?php
        if (isset($_GET['error'])) {
            echo "<p>" . htmlspecialchars($_GET['error']) . "</p>";
        } else {
            echo "<p>An unknown error occurred.</p>";
        }
        ?>
    </div>

    <div class="center">
        <a href="../index.php"><button>Επιστροφή στην αρχική</button></a>
    </div>

  </main>
  <div id="footer_container"></div>
</body>
</html>