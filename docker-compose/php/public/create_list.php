<?php

require_once '../src/auth_check.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Want to make a New List?
    <form action="../src/make_list.php" method="post">
        <input type="text" name="list_name" placeholder="List Name">
        <input type="text" name="list_description" placeholder="List Description">
        <input type="submit" value="Create List">
    </form>
</body>
</html>