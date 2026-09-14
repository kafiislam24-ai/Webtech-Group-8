<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Employee') {
        header("Location: employee_dashboard.php");
    } elseif ($_SESSION['role'] === 'Manager') {
        header("Location: manager_dashboard.php");
    } elseif ($_SESSION['role'] === 'Technician') {
        header("Location: technician_dashboard.php");
    }
    exit();
}

$saved_email   = $_COOKIE['saved_email'] ?? '';
$is_remembered = !empty($saved_email) ? 'checked' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Login</title>
    <!-- External CSS link -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Equipment & Maintenance Request System</h1>
        <div>
            <a href="register.php">Create Account</a>
        </div>
    </header>

    <div class="container">
        <div class="form-card">
            <h2>User Login</h2>

            <?php if (isset($_GET['registered'])): ?>
                <p style="color: #28a745; font-size: 13px; margin-bottom: 15px; font-weight: bold;">
                    Registration successful! Please log in with your credentials.
                </p>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <p class="error-msg" style="margin-bottom: 15px;">
                    Invalid email, password, or role selection.
                </p>
            <?php endif; ?>

            <form id="loginForm" action="../controllers/loginController.php" method="POST" onsubmit="return validateLogin()">
                
                <div class="form-group">
                    <label for="loginEmail">Email Address:</label>
                    <input type="email" 
                           id="loginEmail" 
                           name="email" 
                           value="<?php echo htmlspecialchars($saved_email); ?>" 
                           placeholder="example@domain.com">
                    <span class="error-msg" id="loginEmailError"></span>
                </div>

                <div class="form-group">
                    <label for="loginPassword">Password:</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password">
                    <span class="error-msg" id="loginPasswordError"></span>
                </div>

                <div class="form-group">
                    <label for="loginRole">Select Your Role:</label>
                    <select id="loginRole" name="role">
                        <option value="">-- Choose Role --</option>
                        <option value="Employee">Employee</option>
                        <option value="Manager">Manager</option>
                        <option value="Technician">Technician</option>
                    </select>
                    <span class="error-msg" id="loginRoleError"></span>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                    <input type="checkbox" id="rememberMe" name="remember_me" value="1" <?php echo $is_remembered; ?>>
                    <label for="rememberMe" style="margin-bottom: 0; font-weight: normal; cursor: pointer;">Remember Me for 7 days</label>
                </div>

                <button type="submit" name="login_btn" class="btn btn-primary">Login</button>
            </form>

            <p class="text-center mt-10" style="font-size: 13px;">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </div>
    </div>

    <script src="../js/validation.js"></script>

</body>
</html>