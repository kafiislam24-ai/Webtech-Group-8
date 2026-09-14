<?php
session_start();

$_SESSION = array();
session_unset();

session_destroy();

if (isset($_COOKIE['saved_email'])) {
    setcookie("saved_email", "", time() - 3600, "/");
}

header("Location: views/login.php");
exit();
?>