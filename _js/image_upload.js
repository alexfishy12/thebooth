$(document).ready(function() {

     // add product to database
     $("form#image_upload").submit(function(e) {
        e.preventDefault();

        let fileInputs = document.querySelectorAll("input[type='file']");
        let files = [];

        fileInputs.forEach(function(input) {
            if (input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    files.push(input.files[i]);
                }
            }
        });
        if (files.length === 0) {
            $("#error_message").html("No images uploaded. (At least 1 is required)");
            return; // No file selected
        }

        var formData = new FormData(this);

        server_request("../_php/image_upload_test.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
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

});