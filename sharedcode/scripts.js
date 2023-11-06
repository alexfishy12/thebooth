//Load shared navbar into container, load login button
$(document).ready(function () {
    $('#navbar-container').load('../sharedcode/nav.html', function() {
        const loginButton = document.getElementById("loginButton");
        if (loginButton) {
            loginButton.addEventListener("click", function() {
                const loginPageUrl = "../webpages/customer_login.html";
                window.location.href = loginPageUrl;
            });
        }
    });
});

//Login Script
$(document).ready(function() {
    console.log("JS connected.")
    $("form#login").submit(function(e) {
        e.preventDefault();
        var values = {};
        $.each($('form#login').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });

        server_request("../_php/login.php", "POST", values).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    window.location.replace("../webpages/main_page.html");
                    break;
                case "failure":
                    $("#error_message").html(response.data);
                    break;
                case "error":
                    console.error(response.data);
                    break;
                default:
                    console.log(response)
                    break;
            }
        });
    })
})
//Server Request Function
function server_request(url, method, data = null) {
    return new Promise(function(resolve) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function (response, status) {
                // AJAX Success, server responded
                try {
                    // Try to parse response as JSON
                    response = JSON.parse(response);
                } catch (e) {
                    // Response is not JSON, Error in PHP was not caught
                    response = {
                        status: 'error', 
                        data: "Error parsing response from server.\n\nJS ERROR\n" + e + "\n\nPHP ERROR\n" + response
                    };
                    resolve(response);
                }
                // Response successfully parsed as JSON, PHP compiled and caught all errors
                resolve(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // AJAX Error, server did not respond
                response = {
                    status: 'error', 
                    data: "AJAX ERROR (" + textStatus + ")\n" + errorThrown
                };
                resolve(response);
            }
        })
    });
}