<?php
session_start();

require_once __DIR__ . '/../models/userModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login_btn'])) {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = trim($_POST['role'] ?? '');

    if (empty($email) || empty($password) || empty($role)) {
        header("Location: ../views/login.php?error=empty_fields");
        exit();
    }

    $user = validateUser($email, $password, $role);

    if ($user) {
        $_SESSION['user_id']   = $user['UserID'];
        $_SESSION['user_name'] = $user['Name'];
        $_SESSION['role']      = $user['RoleName'];

        if (isset($_POST['remember_me'])) {
            setcookie("saved_email", $email, time() + (86400 * 7), "/");
        } else {
            if (isset($_COOKIE['saved_email'])) {
                setcookie("saved_email", "", time() - 3600, "/");
            }
        }

        if ($user['RoleName'] === 'Employee') {
            header("Location: ../views/employee_dashboard.php");
        } elseif ($user['RoleName'] === 'Manager') {
            header("Location: ../views/manager_dashboard.php");
        } elseif ($user['RoleName'] === 'Technician') {
            header("Location: ../views/technician_dashboard.php");
        } else {
            header("Location: ../views/login.php?error=unknown_role");
        }
        exit();

    } else {
        header("Location: ../views/login.php?error=invalid_credentials");
        exit();
    }

} else {
    header("Location: ../views/login.php");
    exit();
}
?>