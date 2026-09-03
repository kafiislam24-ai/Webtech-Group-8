<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header><h1>Equipment & Maintenance Request System</h1></header>
    <main class="auth-wrapper">
        <section class="form-card">
            <h2>Create an Account</h2>
            <form id="registerForm" action="../controllers/AuthController.php" method="POST">
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label for="regName">Full Name:</label>
                    <input type="text" id="regName" name="fullname" placeholder="Enter full name">
                    <span class="error-msg" id="regNameError"></span>
                </div>

                <div class="form-group">
                    <label for="regEmail">Email Address:</label>
                    <input type="email" id="regEmail" name="email" placeholder="example@domain.com">
                    <span class="error-msg" id="regEmailError"></span>
                </div>

                <div class="form-group">
                    <label for="regPassword">Password:</label>
                    <input type="password" id="regPassword" name="password" placeholder="Create a password">
                    <span class="error-msg" id="regPasswordError"></span>
                </div>

                <div class="form-group">
                    <label for="regConfirmPassword">Confirm Password:</label>
                    <input type="password" id="regConfirmPassword" name="confirm_password" placeholder="Re-enter password">
                    <span class="error-msg" id="regConfirmPasswordError"></span>
                </div>

                <div class="form-group">
                    <label for="regRole">Role:</label>
                    <select id="regRole" name="role">
                        <option value="">-- Select Account Role --</option>
                        <option value="Employee">Employee</option>
                        <option value="Manager">Manager</option>
                        <option value="Technician">Technician</option>
                    </select>
                    <span class="error-msg" id="regRoleError"></span>
                </div>

                <button type="submit" class="btn-primary">Register</button>
            </form>
            <p class="switch-link">Already registered? <a href="login.php">Back to Login</a></p>
        </section>
    </main>
    <script src="../assets/js/validation.js"></script>
    <script src="../assets/js/ajax_handler.js"></script>
</body>
</html>