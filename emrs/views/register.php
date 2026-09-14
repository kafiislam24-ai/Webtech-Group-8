<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Register</title>
    <!-- 1. CSS Stylesheet Link -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Equipment & Maintenance Request System</h1>
        <div>
            <a href="login.php">Back to Login</a>
        </div>
    </header>

    <div class="container">
        <div class="form-card">
            <h2>Create an Account</h2>

            <!-- Feedback message from registerController.php if registration failed -->
            <?php if (isset($_GET['error'])): ?>
                <p class="error-msg" style="margin-bottom: 10px;">
                    Registration failed. Please verify your inputs and try again.
                </p>
            <?php endif; ?>

            <!-- 2. Form with Client-Side Validation and Controller Action -->
            <form id="registerForm" action="../controllers/registerController.php" method="POST" onsubmit="return validateRegister()">
                
                <!-- Full Name -->
                <div class="form-group">
                    <label for="regName">Full Name:</label>
                    <input type="text" id="regName" name="name" placeholder="Enter your full name">
                    <span class="error-msg" id="regNameError"></span>
                </div>

                <!-- Email (with Live AJAX Check) -->
                <div class="form-group">
                    <label for="regEmail">Email Address:</label>
                    <input type="email" id="regEmail" name="email" placeholder="example@domain.com" onkeyup="checkEmail()">
                    <!-- Span for Client Validation errors -->
                    <span class="error-msg" id="regEmailError"></span>
                    <!-- Span for Live AJAX Server Feedback -->
                    <span id="emailStatus" style="font-size: 12px; font-weight: bold; display: block; margin-top: 4px;"></span>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="regPassword">Password:</label>
                    <input type="password" id="regPassword" name="password" placeholder="At least 6 characters">
                    <span class="error-msg" id="regPasswordError"></span>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="regConfirmPassword">Confirm Password:</label>
                    <input type="password" id="regConfirmPassword" name="confirm_password" placeholder="Re-type your password">
                    <span class="error-msg" id="regConfirmPasswordError"></span>
                </div>

                <!-- Role Dropdown -->
                <div class="form-group">
                    <label for="regRole">Select Role:</label>
                    <select id="regRole" name="role">
                        <option value="">-- Choose Role --</option>
                        <option value="Employee">Employee</option>
                        <option value="Manager">Manager</option>
                        <option value="Technician">Technician</option>
                    </select>
                    <span class="error-msg" id="regRoleError"></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="register_btn" class="btn btn-primary">Register Account</button>
            </form>

            <p class="text-center mt-10" style="font-size: 13px;">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </div>

    <!-- 3. Client-Side JavaScript Form Validation -->
    <script src="../js/validation.js"></script>

    <!-- 4. AJAX (GET) Live Email Availability Function -->
    <script>
    function checkEmail() {
        let email = document.getElementById("regEmail").value.trim();
        let statusSpan = document.getElementById("emailStatus");

        // Reset display if field is too short or lacks '@'
        if (email.length < 4 || !email.includes("@")) {
            statusSpan.innerText = "";
            return;
        }

        // Asynchronous GET request to the Controller
        fetch("../controllers/checkEmailController.php?email=" + encodeURIComponent(email))
            .then(response => response.text())
            .then(data => {
                if (data === "taken") {
                    statusSpan.style.color = "#d9534f"; // Red
                    statusSpan.innerText = "Email is already registered!";
                } else if (data === "available") {
                    statusSpan.style.color = "#28a745"; // Green
                    statusSpan.innerText = "Email is available.";
                } else {
                    statusSpan.innerText = "";
                }
            })
            .catch(error => {
                console.error("AJAX error:", error);
            });
    }
    </script>

</body>
</html>