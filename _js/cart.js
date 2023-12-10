function add_to_cart(product) {
    // Retrieve or initialize the cart
    var cart = get_cart();

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

function empty_cart() {
    update_cart_cookie([]);
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

function generate_cart_item(cart_item) {
    var cart = get_cart();
        var cart = JSON.parse(getCookie('cart'));
        cart.forEach(function (item) {
            var product_data = get_product(item.id);

            item.name = product_data.name;
            item.description = product_data.description;
            item.price = product_data.price;
            // item.image = product_data.image;

            var cardHtml = `<div class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="${item.image}" class="img-fluid rounded-start" alt="${item.name}">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">${item.name}</h5>
                                            <p class="card-text">${item.description}</p>
                                            <p class="card-text"><small class="text-muted">${item.price}</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

            console.log(cardHtml); // or append cardHtml to the DOM
        });
}

function get_product(id) {
    // Implement your logic to fetch product data based on the given id
    // and return the product data object
    // Example:
    return {
        name: "Product Name",
        description: "Product Description",
        price: 10.99,
        image: "product-image.jpg"
    };
}