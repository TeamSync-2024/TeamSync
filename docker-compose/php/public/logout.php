<?php

//logout.php
session_start();
session_unset();
session_destroy();
?>

<html>
<head>
    <title>Logout</title>
</head>
<body>
    <h1>Logout</h1>
    <p>You logged out successfully. Click <a href="../public/login.html">here</a> to login again.</p>

</body>
</html>

