
function validateLogin() {
    let isValid = true;

    let email = document.getElementById("loginEmail").value.trim();
    let password = document.getElementById("loginPassword").value.trim();
    let role = document.getElementById("loginRole").value;

    document.getElementById("loginEmailError").innerText = "";
    document.getElementById("loginPasswordError").innerText = "";
    document.getElementById("loginRoleError").innerText = "";

    if (email === "") {
        document.getElementById("loginEmailError").innerText = "Email is required.";
        isValid = false;
    } else if (!email.includes("@") || !email.includes(".")) {
        document.getElementById("loginEmailError").innerText = "Please enter a valid email address.";
        isValid = false;
    }

    if (password === "") {
        document.getElementById("loginPasswordError").innerText = "Password is required.";
        isValid = false;
    } else if (password.length < 6) {
        document.getElementById("loginPasswordError").innerText = "Password must be at least 6 characters.";
        isValid = false;
    }

    if (role === "") {
        document.getElementById("loginRoleError").innerText = "Please select your role.";
        isValid = false;
    }

    return isValid;
}


function validateRegister() {
    let isValid = true;

    let name = document.getElementById("regName").value.trim();
    let email = document.getElementById("regEmail").value.trim();
    let password = document.getElementById("regPassword").value.trim();
    let confirmPassword = document.getElementById("regConfirmPassword").value.trim();
    let role = document.getElementById("regRole").value;

    document.getElementById("regNameError").innerText = "";
    document.getElementById("regEmailError").innerText = "";
    document.getElementById("regPasswordError").innerText = "";
    document.getElementById("regConfirmPasswordError").innerText = "";
    document.getElementById("regRoleError").innerText = "";

    if (name === "") {
        document.getElementById("regNameError").innerText = "Full Name is required.";
        isValid = false;
    } else if (name.length < 3) {
        document.getElementById("regNameError").innerText = "Name must be at least 3 characters long.";
        isValid = false;
    }

    if (email === "") {
        document.getElementById("regEmailError").innerText = "Email is required.";
        isValid = false;
    } else if (!email.includes("@") || !email.includes(".")) {
        document.getElementById("regEmailError").innerText = "Please enter a valid email address.";
        isValid = false;
    }

    if (password === "") {
        document.getElementById("regPasswordError").innerText = "Password is required.";
        isValid = false;
    } else if (password.length < 6) {
        document.getElementById("regPasswordError").innerText = "Password must be at least 6 characters.";
        isValid = false;
    }

    if (confirmPassword === "") {
        document.getElementById("regConfirmPasswordError").innerText = "Please confirm your password.";
        isValid = false;
    } else if (password !== confirmPassword) {
        document.getElementById("regConfirmPasswordError").innerText = "Passwords do not match.";
        isValid = false;
    }

    if (role === "") {
        document.getElementById("regRoleError").innerText = "Please select an account role.";
        isValid = false;
    }

    return isValid;
}