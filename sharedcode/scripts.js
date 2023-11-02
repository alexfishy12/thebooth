//Load Shared Code into file on startup
$(document).ready(function () {
    $('#navbar-container').load('../sharedcode/nav.html', function() {
        const loginButton = document.getElementById("loginButton");
        if (loginButton) {
            loginButton.addEventListener("click", function() {
                const loginWindow = window.open("../sharedcode/login.html", "Login", "width=400, height=300");
                loginWindow.focus();
            });
        }
    });
});

