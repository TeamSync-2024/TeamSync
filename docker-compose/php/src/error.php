<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
    <h1>Error</h1>
    <?php
    if (isset($_GET['error'])) {
        echo "<p>" . htmlspecialchars($_GET['error']) . "</p>";
    } else {
        echo "<p>An unknown error occurred.</p>";
    }
    ?>
</body>
</html>