function add_to_cart(product) {
    // Get the product data from the form

    console.log(product)
    // Retrieve or initialize the cart
    var cart = get_cart();
    console.log(cart)

    // Add the product to the cart
    cart.push(product);

    // Save the updated cart to the cookie
    update_cart_cookie(cart);
}

function update_cart_cookie(cart) {
    var customer_account_info = document.cookie.split('; ').find(row => row.startsWith('customer_account_info=')).split('=')[1];
    customer_account_info = JSON.parse(decodeURIComponent(customer_account_info));
    customer_account_info.cart = cart;
    document.cookie = 'customer_account_info=' + encodeURIComponent(JSON.stringify(customer_account_info)) + '; path=/; expires=' + new Date(new Date().getTime() + 86400000).toUTCString(); // Expires in 1 day
    update_cart_count();
}

function get_cart() {
    var customer_account_info = document.cookie.split('; ').find(row => row.startsWith('customer_account_info=')).split('=')[1];
    customer_account_info = JSON.parse(decodeURIComponent(customer_account_info));
    if (customer_account_info.cart) {
        return customer_account_info.cart;
    }
    return [];
}

function clear_cart() {
    update_cart_cookie([]);
}

function remove_from_cart(product_id) {
    var cart = get_cart();
    var index = cart.findIndex(product => product.id == product_id);
    if (index > -1) {
        cart.splice(index, 1);
    }
    update_cart_cookie(cart);
}

function update_cart_product_quantity(product_id, quantity) {
    var cart = get_cart();
    var index = cart.findIndex(product => product.id == product_id);
    if (index > -1) {
        cart[index].quantity = quantity;
    }
    update_cart_cookie(cart);
}

function update_cart_count() {
    var cart = get_cart();
    var count = cart.length;
    $("#cart_count").html(count);
}