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
        var formData = new FormData(this);
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }
        server_request("../_php/customer_create_account.php", "POST", formData).then(function(response) {
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