<?php
require_once 'auth_check.php';
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current user's ID
$user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

// SQL query to fetch tasks assigned to the current user that are in lists not created by the current user
$sql = "SELECT t.*, GROUP_CONCAT(DISTINCT u.username) AS assigned_users
        FROM tasks t
        JOIN task_assignments ta ON t.id = ta.task_id
        JOIN users u ON ta.user_id = u.id
        JOIN task_lists tl ON t.task_list_id = tl.id
        WHERE ta.user_id = ? AND tl.user_id != ?
        GROUP BY t.id
        ORDER BY t.due_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

function toJson($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "title" => $row['title'],
            "description" => $row['description'],
            "due_date" => $row['due_date'],
            "status" => $row['status'],
            "id" => $row['id'],
            "assigned_users" => $row['assigned_users']
        );
    }
    return json_encode($data);
}

$data = toJson($result);
echo $data;

$stmt->close();
$conn->close();
?>
