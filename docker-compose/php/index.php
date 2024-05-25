<?php
// Database connection parameters
$servername = "di_inter_tech_mysql"; // Change this to your database server name if necessary
$username = "webuser"; // Change this to your database username
$password = "webpass"; // Change this to your database password
$dbname = "di_internet_technologies_project"; // Change this to your database name

// First, include the authentication checker
require_once './src/auth_check.php';

// Now, proceed with the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

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
