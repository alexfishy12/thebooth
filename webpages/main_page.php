<!DOCTYPE html>
<html>
<head>
    <title>The Booth</title>
    <!-- Set charset and viewport -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Load bootstrap icons and stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../sharedcode/styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navigation-->
    <div id="navbar-container"><?php include("../sharedcode/nav.php"); ?></div>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">The Booth Demonstation Site</h1>
                <p class="lead fw-normal text-white-50 mb-0">Browse clothing and see how they look on you with our virtual try-on booth!</p>
            </div>
        </div>
    </header>
    <!-- Selection Buttons -->
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center"> 
            <button class="btn btn-outline-dark" id="Shirt">Shirts</button>
            <button class="btn btn-outline-dark" id="Jacket">Jackets</button>
            <button class="btn btn-outline-dark" id="Dress">Dresses</button>
            <button class="btn btn-outline-dark" id="Pants">Pants</button>
        </div>
    </div>
    <!-- Search Bar -->
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <form action="/search" method="GET" style="display: flex;">
                <input type="text" name="query" placeholder="Search..." style="flex: 1; margin-right: 10px;">
                <button type="submit" class="btn btn-outline-dark">Search</button>
            </form>
        </div>
    </div>    
    <!-- Items Section -->
    <section id="items" class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="items-row">
            </div>
        </div>
    </section>
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script>
        var $products;
        //Load Products on page load
        $(document).ready(function() {
            $.ajax({
                url: '../_php/load_products.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $products = data;
                    var $row = $('#items-row');
                    $row.empty();
                    data.forEach(function(product) {
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
                }
            });
        });
        //Update Shown Items on section selection
        $(".btn").click(function () {
            var sortItem = $(this).attr("id");
            let selectedProducts = $products.filter(product => product.category === sortItem);
            var $row = $('#items-row');
            $row.empty();
            console.log(selectedProducts)
            selectedProducts.forEach(function(product) {
                console.log("Works");
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
    </script>
</body>
</html>