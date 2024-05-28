<?php
require_once 'auth_check.php';
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the authenticated user's ID (assuming you have a way to get it, e.g., from the session)
$user_id = $_SESSION['user_id'];

$sql = "SELECT t.id, t.task_list_id, t.title, t.description, t.status, t.due_date, tl.title AS list_name
        FROM tasks t
        JOIN task_assignments ta ON t.id = ta.task_id
        JOIN task_lists tl ON t.task_list_id = tl.id
        WHERE ta.user_id = ?
        ORDER BY t.due_date ASC";
$stmt = $conn->prepare($sql);

function toJson($result) {
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "id" => $row['id'],
            "task_list_id" => $row['task_list_id'],
            "title" => $row['title'],
            "description" => $row['description'],
            "status" => $row['status'],
            "due_date" => $row['due_date'],
            "list_name" => $row['list_name']
        );
    }

    return json_encode($data);
}

// Bind the user_id parameter to the statement
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = toJson($result);

echo $data;

$stmt->close();
$conn->close();
?>