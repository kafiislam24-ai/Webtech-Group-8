<?php
require_once __DIR__ . '/../models/userModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register_btn'])) {

    $name             = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email            = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password         = trim($_POST['password'] ?? '');
    $confirmPassword  = trim($_POST['confirm_password'] ?? '');
    $role             = htmlspecialchars(trim($_POST['role'] ?? ''));

    
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        header("Location: ../views/register.php?error=empty_fields");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../views/register.php?error=invalid_email");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: ../views/register.php?error=password_short");
        exit();
    }

    if ($password !== $confirmPassword) {
        header("Location: ../views/register.php?error=password_mismatch");
        exit();
    }

    if (isEmailTaken($email)) {
        header("Location: ../views/register.php?error=email_taken");
        exit();
    }

    $isRegistered = registerUser($name, $email, $password, $role);

    if ($isRegistered) {
        header("Location: ../views/login.php?registered=1");
        exit();
    } else {
        header("Location: ../views/register.php?error=db_error");
        exit();
    }
} else {
    header("Location: ../views/register.php");
    exit();
}
?>