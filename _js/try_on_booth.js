$(document).ready(function() {

    // On click
    $("form#try_on_booth").submit(function(e) {
        e.preventDefault();

        // hide generate button, show loading spinner
        $("#generate_button").hide();
        $("#booth_image").hide();
        $("#booth_image").html("");
        $("#booth_image_spinner").show();
        setTimeout(generate_booth_image, 1000);
    });
});

function generate_booth_image() {
    var formData = get_form_data();

    server_request("http://knet-lambda:8080/test.php", "POST", formData).then(function(response) {
        // handle response from server
        switch (response.status) {
            case "success":
                console.log(response.data);
                display_booth_image(response.data);
                break;
            case "failure":
                console.log("Failure\n" + response.data)
                display_booth_error("<span class='error'>Generation failed: " + response.data + "</span>")
                break;
            case "error":
                console.error(response.data);
                display_booth_error("<span class='error'>Generation failed: Internal server error</span>")
                break;
            default:
                console.log(response)
                display_booth_error("<span class='error'>Generation failed: Internal server error</span>")
                break;
        }
    });
}

function reset_booth() {
    $("#reset_booth").hide();
    $("#booth_image").hide();
    $("#booth_image").html("");
    $("#booth_image_spinner").hide();
    $("#booth_error_message").hide();
    $("#booth_error_message").html("");
    $("#generate_button").show();
}

function display_booth_error(error_message) {
    $("#booth_image_spinner").hide();
    $("#booth_error_message").show();
    $("#booth_error_message").html(error_message);
    $("#reset_booth").show();
}

function display_booth_image(image) {
    // grab image from server
    // var image = "<img src='http://knet-lambda:8080/test.php' alt='booth image' />";

    $("#booth_image_spinner").hide();
    $("#booth_image").html(image);
    $("#booth_image").show();
    $("#reset_booth").show();
}

function get_form_data() {
    // get image name of the active carousel item for the product carousel
    var product_image = $("#product_image_carousel .carousel-inner .carousel-item.active img").attr("src");

    // get image name of the active carousel item for the customer image carousel
    var customer_image = $("#customer_image_carousel .carousel-inner .carousel-item.active img").attr("src");

    console.log(product_image);
    console.log(customer_image);
    // set hidden inputs to the image names
    $("input#product_image").val(product_image);
    $("input#customer_image").val(customer_image);

    var formData = new FormData();

    var product_image = $("form#try_on_booth input#input_product_image").val();
    var customer_image = $("form#try_on_booth input#input_customer_image").val();
    var product_id = $("form#try_on_booth input#input_product_id").val();
    var customer_id = $("form#try_on_booth input#input_customer_id").val();

    console.log(
        {
            product_image: product_image,
            customer_image: customer_image,
            product_id: product_id,
            customer_id: customer_id
        }
    )

    formData.append("product_image", product_image);
    formData.append("customer_image", customer_image);
    formData.append("product_id", product_id);
    formData.append("customer_id", customer_id);
}