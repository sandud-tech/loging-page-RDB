
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("adminLoginForm");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    loginForm.addEventListener("submit", function (event) {
        const email = emailInput.value.trim();
        const password = passwordInput.value;

        if (!email || !password.trim()) {
            event.preventDefault();
            alert("Please enter your email and password.");
            return;
        }

        if (!emailInput.checkValidity()) {
            event.preventDefault();
            alert("Please enter a valid email address.");
            emailInput.focus();
        }
    });
});
