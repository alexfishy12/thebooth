var cart_subtotal;
var cart_item_count;

$(document).ready(function() {
    // check if user is logged in
    if (!document.cookie.includes('customer_account_info=')) {
        return;
    }

    checkout_buttons_disabled(true);
    load_cart_list();
    update_cart_subtotal();

    // check if user clicks checkout button
    $("form#place_order").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        place_order(formData);
    });
});

function place_order(formData) {
    //show loading spinner and hide checkout information
    $("#checkout_information").hide();
    $("#place_order_spinner").show();

    server_request("../_php/r_customer_place_order.php", "POST", formData).then(function(response) {
        // handle response from server
        switch (response.status) {
            case "success":
                $("#place_order_spinner").hide();
                $("#success_message").html(response.data);
                empty_cart();
                setTimeout(function() {
                    window.location.replace("main_page.php");
                }, 2000);
                break;
            case "failure":
                show_error(response.data);
                $("#place_order_spinner").hide();
                break;
            case "error":
                show_error("Order failed! Internal server error.");
                $("#place_order_spinner").hide();
                console.error(response.data);
                break;
            default:
                console.log(response)
                break;
        }
    });
}

function show_error(error_message) {
    $("#error_message").html(error_message);
    $("#error_screen").show();
}

function close_error() {
    $("#error_message").html("");
    $("#error_screen").hide();
    $("#checkout_information").show();
}

function load_cart_list() {
    var cart = get_cart();

    // check if cart is empty
    if (cart.length == 0) {
        $("#checkout_list").html("<div class='text-muted'>Your cart is empty</div>");
        window.location.replace('main_page.php')
        checkout_buttons_disabled(true);
        return;
    }
    checkout_buttons_disabled(false);

    cart_subtotal = 0;
    cart_item_count = 0;


    // get data from each cart item

    $("#checkout_list").html('');
    cart.forEach(function(item) {
        var formData = new FormData();
        formData.append("product_id", item.id);
        server_request("../_php/r_get_single_product.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    parse_product_data(response.data, item);
                    break;
                case "failure":
                    console.log(response.data);
                    break;
                case "error":
                    console.error(response.data);
                    break;
                default:
                    console.log(response)
                    break;
            }
        });
    });
}

function checkout_buttons_disabled(bool) {
    console.log("Buttons disabled: " + bool)
    $("#button_place_order").prop("disabled", bool);
    $("#button_cancel_checkout").prop("disabled", bool);
}

function update_cart_subtotal() {
    var cart = get_cart();

    // check if cart is empty
    if (cart.length == 0) {
        $("#cart_subtotal").html("");
        return;
    }
    
    cart_subtotal = 0;
    cart_item_count = 0;

    cart.forEach(function(item) {
        cart_subtotal += parseFloat(item.price * item.quantity);
        cart_item_count += parseInt(item.quantity);
    });
    $("#cart_subtotal").html("Checkout total (" + cart_item_count + " items): <strong>$" + cart_subtotal + "</strong>");
}

function parse_product_data(db_data, cart_data) {
    /*
    console.log("DB DATA")
    console.log(JSON.stringify(db_data))
    console.log("CART DATA")
    console.log(JSON.stringify(cart_data));
    */
    // generate cart item
    var cart_item = generate_cart_item(db_data, cart_data);

    // add cart item to cart list
    $("#checkout_list").append(cart_item);
}

function generate_cart_item(db_data, cart_data) {
    // Find the color and size details
    const color = db_data.colors.find(c => c.id == cart_data.color)?.color || 'Unknown Color';
    const size = db_data.sizes.find(s => s.id == cart_data.size)?.size || 'Unknown Size';

    var image_src = "";
    // if product doesn't have any images
    if (db_data.images.length == 0 || db_data.images[0].image_og == null) {
        image_src = get_placeholder_img(db_data);
    }
    else {
        image_src = "../__uploads/product_images/" + cart_data.id + "/" + db_data.images[0].image_og;
    }

    // Generate the HTML card
    const cardHtml = `
        <div class="card mb-3" id="cart_card_${db_data.id}">
            <div class="row g-0">
                <div class="card-header col-md-2 d-flex align-items-center justify-content-center">
                    <div class="cart-thumbnail">
                        <img src="${image_src}" alt="${db_data.name}">
                    </div>
                </div>
                <div class="col">
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-start">
                                <h5 class="card-title">${db_data.name}</h5>
                                <p class="card-text">
                                ${db_data.description}<br>
                                <small class="text-muted">
                                    Size: ${size}<br>
                                    Color: ${color}<br>
                                </small>
                                </p>
                                <p class="card-text">Quantity: ${cart_data.quantity}</p>
                            </div>
                            <div class="col-auto text-end">
                                <div class="row">
                                    <h3>$${cart_data.price}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="product_id[]" value="${db_data.id}">
        <input type="hidden" name="color_id[]" value="${cart_data.color}">
        <input type="hidden" name="size_id[]" value="${cart_data.size}">
        <input type="hidden" name="order_quantity[]" value="${cart_data.quantity}">
    `;

    // Return the HTML string
    return cardHtml;
}