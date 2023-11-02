//Load shared navbar into container
$(document).ready(function () {
    $('#navbar-container').load('../sharedcode/nav.html', function() {
        const loginButton = document.getElementById("loginButton");
        if (loginButton) {
            loginButton.addEventListener("click", function() {
                const loginPageUrl = "../webpages/login.html";
                window.location.href = loginPageUrl;
            });
        }
    });
});