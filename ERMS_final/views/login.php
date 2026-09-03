<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Employee') header("Location: employee_dashboard.php");
    elseif ($_SESSION['role'] === 'Manager') header("Location: manager_dashboard.php");
    elseif ($_SESSION['role'] === 'Technician') header("Location: technician_dashboard.php");
    exit();
}
$saved_email = $_COOKIE['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header><h1>Equipment & Maintenance Request System</h1></header>
    <main class="auth-wrapper">
        <section class="form-card">
            <h2>User Login</h2>
            <!-- Points to AuthController -->
            <form id="loginForm" action="../controllers/AuthController.php" method="POST">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label for="loginEmail">Email Address:</label>
                    <input type="email" id="loginEmail" name="email" value="<?php echo htmlspecialchars($saved_email); ?>" placeholder="example@domain.com">
                    <span class="error-msg" id="loginEmailError"></span>
                </div>

                <div class="form-group">
                    <label for="loginPassword">Password:</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Enter password">
                    <span class="error-msg" id="loginPasswordError"></span>
                </div>

                <div class="form-group">
                    <label for="loginRole">Select Role:</label>
                    <select id="loginRole" name="role">
                        <option value="">-- Select Role --</option>
                        <option value="Employee">Employee</option>
                        <option value="Manager">Manager</option>
                        <option value="Technician">Technician</option>
                    </select>
                    <span class="error-msg" id="loginRoleError"></span>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="rememberMe" name="remember_me" value="1" <?php echo !empty($saved_email) ? 'checked' : ''; ?>>
                    <label for="rememberMe">Remember Me</label>
                </div>

                <button type="submit" class="btn-primary">Login</button>
            </form>
            <p class="switch-link">Don't have an account? <a href="register.php">Create an Account</a></p>
        </section>
    </main>
    <script src="../assets/js/validation.js"></script>
</body>
</html>