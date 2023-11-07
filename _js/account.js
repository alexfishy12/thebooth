$(document).ready(function() {
    console.log("JS connected.")
    $("form#customer_login").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        server_request("../_php/r_customer_login.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#sign_in_form").hide();
                    $("#success_message").html(response.data);
                    setTimeout(function() {
                        window.location.replace("main_page.php");
                    }, 2000);
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

    $("form#manager_login").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        server_request("../_php/r_manager_login.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#sign_in_form").hide();
                    $("#success_message").html(response.data);
                    setTimeout(function() {
                        window.location.replace("main_page.php");
                    }, 2000);
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

    $("form#admin_login").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        server_request("../_php/r_admin_login.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#sign_in_form").hide();
                    $("#success_message").html(response.data);
                    setTimeout(function() {
                        window.location.replace("admin.php");
                    }, 2000);
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
        
        server_request("../_php/r_customer_create_account.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#sign_up_form").hide();
                    $("#success_message").html(response.data);
                    setTimeout(function() {
                        window.location.replace("main_page.php");
                    }, 2000);
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