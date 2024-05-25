<?php

//logout.php
session_start();
session_unset();
session_destroy();
echo "You logged out successfully. Click <a href='../public/login.html'>here</a> to login again.";
?>