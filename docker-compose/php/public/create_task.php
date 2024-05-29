<?php
require_once '../src/auth_check.php';
require_once '../src/config.php';

$list_id = isset($_GET['list_id'])? $_GET['list_id'] : null;
if (!$list_id) {
    header("Location: ../public/lists.php");
    exit();
}
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
        <h2>Δημιουργείτε μια νέα εργασία για τη λίστα: <b><?php echo ($list_name);?></b></h2>
    </div>
    <div class="center">
        <div class="max_width">
            <form action="../src/make_task.php?list_id=<?php echo htmlspecialchars($list_id);?>" method="post">

                <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($list_id);?>">

                <label for="task_title"><b>Όνομα Εργασίας:</b></label><br>
                <input type="text" name="task_title" placeholder="Όνομα εργασίας" required><br><br>

                <label for="task_description"><b>Περιγραφή Εργασίας:</b></label><br>
                <textarea name="task_description" cols="26" rows="3" placeholder="Περιγραφή εργασίας" required></textarea><br><br>

                <label for="task_due_date"><b>Ημερομηνία Προθεσμίας:</b></label><br>
                <input type="date" name="task_due_date" required><br><br>
                
                <div class="center">
                    <button type="submit" value="Create Task">Δημιουργία Εργασίας</button> 
                </div>
                
            </form>
        </div>
    </div>
    </main>
  <div id="footer_container"></div>
</body>
</html>