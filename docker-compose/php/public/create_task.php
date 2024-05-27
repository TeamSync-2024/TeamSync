<?php
require_once '../src/auth_check.php';
require_once '../src/config.php';

$list_id = isset($_GET['list_id'])? $_GET['list_id'] : null;
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

if (!$list_id) {
    die("List ID is missing.");
}

$sql = "SELECT title FROM task_lists WHERE id =?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $list_id); // Adjust the type according to your list_id type
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $list_name = $row['title'];
} else {
    echo "No list found with the given ID.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Display the list_name -->
    <p>You are making a task for: <?php echo ($list_name);?></p>
    
    New task form
    <form action="../src/make_task.php?list_id=<?php echo htmlspecialchars($list_id);?>" method="post">
        <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($list_id);?>">
        <input type="text" name="task_title" placeholder="Task Name" required>
        <textarea name="task_description" placeholder="Task Description" required></textarea>
        <input type="date" name="task_due_date" required>
        <input type="submit" value="Create Task">
    </form>
</body>
</html>
