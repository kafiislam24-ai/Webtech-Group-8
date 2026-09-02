<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Register</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>EMRS Account Registration</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $error): ?>
                    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?url=auth/processRegister" method="POST" id="registerForm">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password (min 6 chars):</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="role">Account Role:</label>
            <select id="role" name="role" required>
                <option value="Employee">Employee</option>
                <option value="Manager">Manager</option>
                <option value="Technician">Technician</option>
            </select>

            <button type="submit">Create Account</button>
        </form>
        <p>Already registered? <a href="index.php?url=auth/login">Log in here</a></p>
    </div>
    <script src="public/js/validation.js"></script>
</body>
</html>