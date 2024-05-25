<?php
session_start();

$servername = "mysql";
$username = "webuser";
$password = "webpass";
$dbname = "di_internet_technologies_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me'])? true : false; // Check if the "Remember Me" checkbox is set
    
    // Prepare and execute the select statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username =?");
    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Check if the user set the Remember me option
            if ($rememberMe){
                setcookie('remember_user', 'true', time() + (86400 * 30), '/'); // 86400 = 1 day
            }

            // Redirect to the appropriate page after successful login
            header("Location: ../index.php");
            exit();
        } else {
            echo "Invalid username or password.";
            $error_message = "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
