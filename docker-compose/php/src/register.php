<?php
$servername = "mysql"; // The service name defined in docker-compose.yml
$username = "webuser";
$password = "webpass";
$dbname = "di_internet_technologies_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $simplepush_key = $_POST['simplepush_key'];

    // Prepare and bind parameters for the check query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username =? OR email =?");
    $stmt->bind_param("ss", $username, $email); // "ss" means two strings

    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Error: Username or email already exists";
    } else {
        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, email, simplepush_key) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $username, $password, $email, $simplepush_key);

        if ($stmt->execute()) {
            header("Location: ../public/login.html");
            send_simplepush_notification($simplepush_key, "Registration Successful", "Welcome, $first_name Your registration was successful.");
        } else {
            echo "Error: ". $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}

function send_simplepush_notification($key, $title, $message) {
    $url = 'https://api.simplepush.io/send';
    $data = array(
        'key' => $key,
        'title' => $title,
        'msg' => $message
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) { 
        // Handle error 
    }

    return $result;
}
?>
