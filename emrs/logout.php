<?php
// Step 1: Resume the active session so PHP knows which session to terminate
session_start();

// Step 2: Clear all session variables from PHP's active memory
$_SESSION = array();
session_unset();

// Step 3: Destroy the session storage file on the server
session_destroy();

// Step 4: Invalidate the persistent "Remember Me" cookie from the browser
if (isset($_COOKIE['saved_email'])) {
    setcookie("saved_email", "", time() - 3600, "/");
}

// Step 5: Redirect back to the login view
header("Location: views/login.php");
exit();
?>