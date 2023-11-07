$(document).ready(function() {
    console.log("JS connected.")
    
    var path = window.location.pathname.split('/');
    var fileName = path.pop() || path.pop(); // handle potential trailing slash

    if (fileName == "admin.php") {
        server_request("../_php/r_get_employees.php", "GET").then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("div#manager_accounts").html(response.data);
                    break;
                case "failure":
                    $("#error_message").html("<span class='error'>" + response.data + "</span>");
                    break;
                case "error":
                    console.error(response.data);
                    break;
                default:
                    console.log(response)
                    break;
            }
        });
    }

    $("form#admin_create_manager_account").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        server_request("../_php/r_admin_create_manager_account.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#create_manager_account_form").hide();
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

    $("form#admin_update_employee_account").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        server_request("../_php/r_admin_update_employee_account.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#sign_up_form").hide();
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

    
})