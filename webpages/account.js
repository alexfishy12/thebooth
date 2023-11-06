$(document).ready(function() {
    console.log("JS connected.")
    $("form#login").submit(function(e) {
        e.preventDefault();
        var values = {};
        $.each($('form#login').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });

        server_request("../_php/customer_login.php", "POST", values).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    window.location.replace("main_page.html");
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

    $("form#customer_create_account").submit(function(e) {
        e.preventDefault();
        var values = {};
        $.each($('form#customer_create_account').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });

        server_request("../_php/customer_create_account.php", "POST", values).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#sign_up_form").hide();
                    $("#success_message").html(response.data);
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