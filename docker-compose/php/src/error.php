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
    <h1>Error</h1>
    <?php
    if (isset($_GET['error'])) {
        echo "<p>" . htmlspecialchars($_GET['error']) . "</p>";
    } else {
        echo "<p>An unknown error occurred.</p>";
    }
    ?>
    </main>
    <div id="footer-container"></div>
</body>
</html>