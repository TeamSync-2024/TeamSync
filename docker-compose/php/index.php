<?php

// First, include the authentication checker
require_once './src/auth_check.php';

require_once './src/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Query to fetch quotes from the database
$sql = "SELECT value FROM test";

$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["value"]. "<br>";
    }
} else {
    echo "0 results";
}

echo " Welcome, ". $_SESSION['username']. "!<br>";
echo " <a href='./public/logout.php'>Logout</a>";

// Close connection
$conn->close();
?>
