<?php
session_start();
$_SESSION = array();
session_unset();
session_destroy();

if (isset($_COOKIE['user_email'])) {
    setcookie('user_email', '', time() - 3600, "/");
}

header("Location: views/login.php");
exit();
?>