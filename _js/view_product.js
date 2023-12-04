$(document).ready(function() {
    // check if form is submitted
    $("form#add_to_cart").submit(function(e) {
        e.preventDefault();

        if (form_is_valid()) {
            var product = {
                id: $("input#product_id").val(),
                quantity: $("input#product_quantity").val(),
                size: $("select#product_size").val(),
                color: $("select#product_color").val(),
                price: $("input#product_price").val()
            };
            add_to_cart(product);
            $("$in_cart_message").html("Item added to cart.")
        }
    });
});

function form_is_valid() {
    if ($("select#product_color").val() == "" || $("select#product_color").val() == null) {
        $("#error_message").html("Please select a color.");
        return false;
    }
    if ($("select#product_size").val() == "" || $("select#product_size").val() == null) {
        $("#error_message").html("Please select a size.");
        return false;
    }
    if ($("input#product_quantity").val() == "" || $("input#product_quantity").val() == 0 || $("input#product_quantity").val() < 0 || $("input#product_quantity").val() == null) {
        $("#error_message").html("Please enter a valid quantity.");
        return false;
    }
    // check if customer is logged in
    if (!document.cookie.includes('customer_account_info=')) {
        $("#error_message").html("Please log in to add items to your cart.");
        return false;
    }
    $("#error_message").html("");
    return true;
}