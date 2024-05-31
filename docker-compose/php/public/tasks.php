<?php
require_once '../src/auth_check.php';
require_once '../src/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$list_id = isset($_GET['list_id']) ? $_GET['list_id'] : null;

if (!$list_id) {
    // If list_id is not provided, redirect to lists page
    header("Location: ../public/lists.php");
    exit(); // Ensure no further code is executed after the redirect
}

// Fetch the title of the task list
$stmt = $conn->prepare("SELECT title FROM task_lists WHERE id = ?");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the title from the result
    $row = $result->fetch_assoc();
    $list_title = $row['title'];
} else {
    // If list_id is invalid, redirect to lists page
    header("Location: ../public/lists.php");
    exit(); // Ensure no further code is executed after the redirect
}
$stmt->close();
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="header_container"></div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div id="navigation_container"></div>
    <?php endif;?>

    <main class="vertical">

        <div class="center">
             <h1>Λίστα: <?php echo htmlspecialchars($list_title); ?></h1>   
        </div>

        <div class="center">
            <h1>Εργασίες</h1> 
        </div>
        
        <div class="center">
            <div id="list_tasks_container"></div>
        </div>
        
        <div class="center">
            <a href="./create_task.php?list_id=<?php echo htmlspecialchars($list_id); ?>">
                <button>Δημιουργία Εργασίας</button>
            </a>
        </div>

    </main>
    <div id="footer_container"></div>
</body>
</html>
