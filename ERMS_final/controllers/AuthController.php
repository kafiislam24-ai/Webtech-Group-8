<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';

function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = clean($_POST['action'] ?? '');

    if ($action === 'login') {
        $email = clean($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = clean($_POST['role'] ?? '');

        if (empty($email) || empty($password) || empty($role)) {
            header("Location: ../views/login.php?error=empty_fields");
            exit();
        }

        $user = UserModel::findByEmailAndRole($conn, $email, $role);

        if ($user && $password === $user['Password']) {
            $_SESSION['user_id']   = $user['UserID'];
            $_SESSION['user_name'] = $user['Name'];
            $_SESSION['role']      = $user['RoleName'];

            if (isset($_POST['remember_me'])) {
                setcookie('user_email', $email, time() + (86400 * 7), "/");
            } else {
                setcookie('user_email', '', time() - 3600, "/");
            }

            if ($role === 'Employee') header("Location: ../views/employee_dashboard.php");
            elseif ($role === 'Manager') header("Location: ../views/manager_dashboard.php");
            elseif ($role === 'Technician') header("Location: ../views/technician_dashboard.php");
            exit();
        } else {
            header("Location: ../views/login.php?error=invalid_credentials");
            exit();
        }
    }

    elseif ($action === 'register') {
        $name = clean($_POST['fullname'] ?? '');
        $email = clean($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        $role = clean($_POST['role'] ?? '');

        if ($password !== $confirmPassword) {
            header("Location: ../views/register.php?error=password_mismatch");
            exit();
        }

        $roleId = UserModel::getRoleIdByName($conn, $role);
        if (!$roleId) {
            header("Location: ../views/register.php?error=invalid_role");
            exit();
        }

        if (UserModel::create($conn, $name, $email, $password, $roleId)) {
            header("Location: ../views/login.php?success=registered");
        } else {
            header("Location: ../views/register.php?error=registration_failed");
        }
        exit();
    }
}
?>