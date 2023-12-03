function add_to_cart(product) {
    // Get the product data from the form

    console.log(product)
    // Retrieve or initialize the cart
    var cart = [];
    if (document.cookie.includes('cart=')) {
        var cartCookie = document.cookie.split('; ').find(row => row.startsWith('cart=')).split('=')[1];
        cart = JSON.parse(decodeURIComponent(cartCookie));
    }

    // Add the product to the cart
    cart.push(product);

    // Save the updated cart to the cookie
    document.cookie = 'cart=' + encodeURIComponent(JSON.stringify(cart)) + '; path=/; expires=' + new Date(new Date().getTime() + 86400000).toUTCString(); // Expires in 1 day
    update_cart_count();
}

function get_cart() {
    if (document.cookie.includes('cart=')) {
        var cartCookie = document.cookie.split('; ').find(row => row.startsWith('cart=')).split('=')[1];
        return JSON.parse(decodeURIComponent(cartCookie));
    }
    return [];
}

function clear_cart() {
    document.cookie = 'cart=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    update_cart_count();
}

function remove_from_cart(product_id) {
    var cart = get_cart();
    var index = cart.findIndex(product => product.id == product_id);
    if (index > -1) {
        cart.splice(index, 1);
    }
    document.cookie = 'cart=' + encodeURIComponent(JSON.stringify(cart)) + '; path=/; expires=' + new Date(new Date().getTime() + 86400000).toUTCString(); // Expires in 1 day
    update_cart_count();
}

function update_cart_product_quantity(product_id, quantity) {
    var cart = get_cart();
    var index = cart.findIndex(product => product.id == product_id);
    if (index > -1) {
        cart[index].quantity = quantity;
    }
    document.cookie = 'cart=' + encodeURIComponent(JSON.stringify(cart)) + '; path=/; expires=' + new Date(new Date().getTime() + 86400000).toUTCString(); // Expires in 1 day
}

function update_cart_count() {
    var cart = get_cart();
    var count = cart.length;
    $("#cart_count").html(count);
}