<?php
ob_start();
require_once 'auth_check.php';

if ($_SESSION['user_role'] !== 'admin') {
    ob_end_clean();
    header("Location: ../index.php");
    exit();
} 

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch lists, tasks, and assigned users
$sql = "SELECT task_lists.id AS list_id, task_lists.title AS list_name, 
            tasks.id AS task_id, tasks.title AS task_name, 
            users.username AS user_username
        FROM task_lists
        LEFT JOIN tasks ON task_lists.id = tasks.task_list_id
        LEFT JOIN task_assignments ON tasks.id = task_assignments.task_id
        LEFT JOIN users ON task_assignments.user_id = users.id";

$result = $conn->query($sql);

// Initialize XML document
$xmlDoc = new DOMDocument('1.0');
$xmlDoc->preserveWhiteSpace = false;
$xmlDoc->formatOutput = true;
$xmlRoot = $xmlDoc->createElement('data');
$xmlDoc->appendChild($xmlRoot);

if ($result->num_rows > 0) {
    // Fetch results and build XML structure
    while($row = $result->fetch_assoc()) {
        $list_id = $row['list_id'];
        $list_name = $row['list_name'];
        $task_id = $row['task_id'];
        $task_name = $row['task_name'];
        $user_username = $row['user_username'];
        
        // Check if list node already exists
        $list_node = $xmlDoc->createElement('list');
        $list_node->setAttribute('id', $list_id);
        $xmlRoot->appendChild($list_node);
        
        $list_name_node = $xmlDoc->createElement('name', $list_name);
        $list_node->appendChild($list_name_node);

        // Add task node to the list node
        if ($task_id) {
            $task_node = $xmlDoc->createElement('task');
            $task_node->setAttribute('id', $task_id);
            $list_node->appendChild($task_node);
            
            $task_name_node = $xmlDoc->createElement('name', $task_name);
            $task_node->appendChild($task_name_node);

            if ($user_username) {
                $assigned_user_node = $xmlDoc->createElement('assigned_user', $user_username);
                $task_node->appendChild($assigned_user_node);
            }
        }
    }
}

// Set headers to download the file
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="tasks_export.xml"');

// Output the XML content
echo $xmlDoc->saveXML();

// Close the connection
$conn->close();

exit();
?>
