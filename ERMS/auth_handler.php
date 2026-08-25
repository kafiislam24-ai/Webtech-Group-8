<?php
session_start();

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';
    $errors = [];

   
    if ($action === 'login') {
        $email = sanitize_input($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = sanitize_input($_POST['role'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }
        $validRoles = ['Employee', 'Manager', 'Technician'];
        if (empty($role) || !in_array($role, $validRoles)) {
            $errors[] = "Please select a valid user role.";
        }

        if (!empty($errors)) {
            echo "<h3>Login Failed:</h3><ul>";
            foreach ($errors as $error) {
                echo "<li style='color:red;'>$error</li>";
            }
            echo "</ul><a href='login.php'>Go Back</a>";
            exit();
        }

        $_SESSION['user_id'] = rand(100, 999);
        $_SESSION['user_name'] = ucfirst(explode('@', $email)[0]); // Derives display name from email
        $_SESSION['role'] = $role;

        if (isset($_POST['remember_me'])) {
            setcookie('user_email', $email, time() + (86400 * 7), "/");
        } else {
            if (isset($_COOKIE['user_email'])) {
                setcookie('user_email', '', time() - 3600, "/");
            }
        }

        if ($role === 'Employee') {
            header("Location: employee_dashboard.php");
        } elseif ($role === 'Manager') {
            header("Location: manager_dashboard.php");
        } elseif ($role === 'Technician') {
            header("Location: technician_dashboard.php");
        }
        exit();
    }

   
    elseif ($action === 'register') {
        $fullname = sanitize_input($_POST['fullname'] ?? '');
        $email = sanitize_input($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');
        $role = sanitize_input($_POST['role'] ?? '');

        if (empty($fullname) || strlen($fullname) < 3) {
            $errors[] = "Full Name must be at least 3 characters.";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }
        $validRoles = ['Employee', 'Manager', 'Technician'];
        if (empty($role) || !in_array($role, $validRoles)) {
            $errors[] = "Please select a valid role.";
        }

        if (!empty($errors)) {
            echo "<h3>Registration Failed:</h3><ul>";
            foreach ($errors as $error) {
                echo "<li style='color:red;'>$error</li>";
            }
            echo "</ul><a href='register.php'>Go Back</a>";
            exit();
        }

        echo "<p style='color:green;'>Registration successful! You may now login.</p>";
        echo "<a href='login.php'>Proceed to Login</a>";
        exit();
    }
}
?>