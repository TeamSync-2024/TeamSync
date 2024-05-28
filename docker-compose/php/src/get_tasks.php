<?php
require_once 'auth_check.php';
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Retrieve the list_id from the URL
$list_id = isset($_GET['list_id'])? $_GET['list_id'] : null;

if (!$list_id) {
    header("Location: ../public/lists.php");
    exit();
}

$sql = "SELECT * FROM tasks WHERE task_list_id =? AND status != 'completed' ORDER BY due_date ASC";
$stmt = $conn->prepare($sql);

function toJson($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "title" => $row['title'],
            "description" => $row['description'],
            "due_date" => $row['due_date'],
            "status" => $row['status'],
            "id" => $row['id']
        );
    }
    return json_encode($data);
}

// Bind the list_id parameter to the statement
$stmt->bind_param("i", $list_id);
$stmt->execute();
$result = $stmt->get_result();
$data = toJson($result);
echo $data;

$stmt->close();
$conn->close();
?>
