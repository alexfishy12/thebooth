// global variables
var $products;

$(document).ready(function() {
    console.log("JS connected.")
    
    var path = window.location.pathname.split('/');
    var fileName = path.pop() || path.pop(); // handle potential trailing slash

    if (fileName == "main_page.php") {
        server_request("../_php/r_get_products.php", "GET").then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    load_products(response.data);
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


    //Update Shown Items on section selection
    $("#Shirt, #Jacket, #Pants, #Dress").click(function () {
        var sortItem = $(this).attr("id");
        console.log(sortItem);
        console.log($products);
        var selectedProducts = $products.filter(product => product.category == sortItem.toLowerCase());
        $row.empty();
        selectedProducts.forEach(function(product) {
            var productCard = `
                <div class="col-12 col-md-3 mb-5">
                    <div class="card h-100">
                        <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder">${product.name}</h5>
                                <p>$${product.price}</p>
                            </div>
                        </div>
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">View Item</a></div>
                        </div>
                    </div>
                </div>
            `;
            $row.append(productCard);
        });
    });
    //Search Bar Function
    $("#Search").click(function() { 
        var text = document.getElementById('searchBox').value.trim().toLowerCase();
        var splitText = text.split(' ');
        var filteredProducts = $products.filter(product => {
            return splitText.every(selection => 
                product.color.toLowerCase().includes(selection) || product.category.toLowerCase().includes(selection) || product.name.toLowerCase().includes(selection)
            );
        });
        $row.empty();
        filteredProducts.forEach(function(product) {
            var productCard = generate_product_card(product)
            $row.append(productCard);
        });
    });
})

function load_products(data) {
    $products = data;
    var $row = $('#items-row');
        
    $row.empty();
    data.forEach(function(product) {
        var productCard = generate_product_card(product)
        $row.append(productCard);
    });
}

function generate_product_card(product) {
    return `
        <div class="col-12 col-md-3 mb-5">
            <div class="card h-100">
                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                <div class="card-body p-4">
                    <div class="text-center">
                        <h5 class="fw-bolder">${product.name}</h5>
                        <p>$${product.price}</p>
                    </div>
                </div>
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="view_product.php?">View Item</a></div>
                </div>
            </div>
        </div>
    `;
}