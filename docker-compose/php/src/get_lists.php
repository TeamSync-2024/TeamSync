<?php
require_once 'auth_check.php';
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

$sql = "SELECT * FROM task_lists WHERE user_id = ?";
$stmt = $conn->prepare($sql);

function toJson($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "title" => $row['title'],
            "description" => $row['description'],
            "created_at" => $row['created_at'],
            "list_id" => $row['id']
        );
    }
    return json_encode($data);
}

// Fetch the lists and return as JSON
$user_id = $_SESSION['user_id'];
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = toJson($result);
echo $data;


$stmt->close();
$conn->close();
?>