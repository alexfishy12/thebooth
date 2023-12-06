$(document).ready(function() {
    // check if user is logged in
    if (!document.cookie.includes('customer_account_info=')) {
        return;
    }

    load_cart_list();
});

function load_cart_list() {
    var cart = get_cart();

    // check if cart is empty
    if (cart.length == 0) {
        return;
    }

    // get data from each cart item

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

function parse_product_data(db_data, cart_data) {
    console.group("Data for Product ID: " + db_data.id);
    console.log("DB DATA")
    console.log(JSON.stringify(db_data))
    console.log("CART DATA")
    console.log(JSON.stringify(cart_data));

    // generate cart item
    var cart_item = generate_cart_item(db_data, cart_data);

    // add cart item to cart list
    $("#cart_list").append(cart_item);
}

function generate_cart_item(db_data, cart_data) {
    // Find the color and size details
    const color = db_data.colors.find(c => c.id == cart_data.color)?.color || 'Unknown Color';
    const size = db_data.sizes.find(s => s.id == cart_data.size)?.size || 'Unknown Size';

    // Generate the HTML card
    const cardHtml = `
        <div class="card">
            <div class="row g-0">
                <div class="card-header col-md-4">
                    <img src="${db_data.images[0].image_og}" class="thumbnail" alt="${db_data.name}">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">${db_data.name}</h5>
                        <p class="card-text">${db_data.description}</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Price: $${cart_data.price}</li>
                            <li class="list-group-item">Quantity: ${cart_data.quantity}</li>
                            <li class="list-group-item">Size: ${size}</li>
                            <li class="list-group-item">Color: ${color}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    `;

    // Return the HTML string
    return cardHtml;
}