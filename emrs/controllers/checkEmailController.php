<?php
require_once __DIR__ . '/../models/userModel.php';

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);

    if (empty($email)) {
        echo "invalid";
        exit();
    }

    $taken = isEmailTaken($email);

    if ($taken) {
        echo "taken";
    } else {
        echo "available";
    }
}
?>