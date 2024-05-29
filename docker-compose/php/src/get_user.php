<?php
require_once 'auth_check.php';
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

$sql = "SELECT first_name,last_name,username,email,simplepush_key FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

function toJson($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "first_name" => $row['first_name'],
            "last_name" => $row['last_name'],
            "username" => $row['username'],
            "email" => $row['email'],
            "simplepush_key" => $row['simplepush_key']
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