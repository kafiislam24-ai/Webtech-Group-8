document.addEventListener("DOMContentLoaded", function () {
    
   
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            let isValid = true;

            clearError("loginEmailError");
            clearError("loginPasswordError");
            clearError("loginRoleError");

            const email = document.getElementById("loginEmail").value.trim();
            const password = document.getElementById("loginPassword").value.trim();
            const role = document.getElementById("loginRole").value;

            if (email === "") {
                showError("loginEmailError", "Email address is required.");
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError("loginEmailError", "Enter a valid email containing '@' and '.'.");
                isValid = false;
            }

            if (password === "") {
                showError("loginPasswordError", "Password is required.");
                isValid = false;
            } else if (password.length < 6) {
                showError("loginPasswordError", "Password must be at least 6 characters.");
                isValid = false;
            }

            if (role === "") {
                showError("loginRoleError", "Please select a user role.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }

    
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            let isValid = true;

            clearError("regNameError");
            clearError("regEmailError");
            clearError("regPasswordError");
            clearError("regConfirmPasswordError");
            clearError("regRoleError");

            const fullname = document.getElementById("regName").value.trim();
            const email = document.getElementById("regEmail").value.trim();
            const password = document.getElementById("regPassword").value.trim();
            const confirmPassword = document.getElementById("regConfirmPassword").value.trim();
            const role = document.getElementById("regRole").value;

            if (fullname === "") {
                showError("regNameError", "Full Name cannot be empty.");
                isValid = false;
            }

            if (email === "") {
                showError("regEmailError", "Email address is required.");
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError("regEmailError", "Enter a valid email containing '@' and '.'.");
                isValid = false;
            }

            if (password === "") {
                showError("regPasswordError", "Password is required.");
                isValid = false;
            } else if (password.length < 6) {
                showError("regPasswordError", "Password must be at least 6 characters.");
                isValid = false;
            }

            if (confirmPassword === "") {
                showError("regConfirmPasswordError", "Please confirm your password.");
                isValid = false;
            } else if (password !== confirmPassword) {
                showError("regConfirmPasswordError", "Passwords do not match.");
                isValid = false;
            }

            if (role === "") {
                showError("regRoleError", "Please select an account role.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }


    const requestForm = document.getElementById("requestForm");
    if (requestForm) {
        requestForm.addEventListener("submit", function (event) {
            let isValid = true;

            clearError("reqEquipmentError");
            clearError("reqPriorityError");
            clearError("reqDescriptionError");

            const equipName = document.getElementById("reqEquipmentName").value.trim();
            const priority = document.getElementById("reqPriority").value;
            const description = document.getElementById("reqDescription").value.trim();

            if (equipName === "") {
                showError("reqEquipmentError", "Equipment name is required.");
                isValid = false;
            }

            if (priority === "") {
                showError("reqPriorityError", "Please select a priority level.");
                isValid = false;
            }

            if (description === "") {
                showError("reqDescriptionError", "Please provide an issue description.");
                isValid = false;
            } else if (description.length < 10) {
                showError("reqDescriptionError", "Description must be at least 10 characters long.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }

    function showError(elementId, message) {
        const errorSpan = document.getElementById(elementId);
        if (errorSpan) {
            errorSpan.innerText = message;
        }
    }

    function clearError(elementId) {
        const errorSpan = document.getElementById(elementId);
        if (errorSpan) {
            errorSpan.innerText = "";
        }
    }

    function isValidEmail(email) {
        return email.indexOf("@") > 0 && email.lastIndexOf(".") > email.indexOf("@") + 1;
    }
});