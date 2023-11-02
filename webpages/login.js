$(document).ready(function() {
    console.log("JS connected.")
    $("form#login").submit(function(e) {
        e.preventDefault();
        var values = {};
        $.each($('form#login').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });

        try_login(values['email'], values['password']).then(function(response) {
            console.log(response);
            if (response.status = "") {
                window.location.replace(response.split(":")[1])
            }
            else if (response.startsWith("ERROR")) {
                $("#errorMessage").html(response.split(":")[1])
            }
        });
    })
})

function try_login(email, password) {
    return new Promise(function(resolve) {
        $.ajax({
            type: 'POST',
            url: '../_php/login.php',
            data: {email: email, password: password},
            success: function (response, status) {
                console.log('AJAX Success.');
                resolve(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('AJAX Error:' + textStatus);
                resolve("Error " . textStatus);
            }
        })
    });
}