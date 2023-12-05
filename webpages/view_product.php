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
    <link href="../sharedcode/custom_styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navigation-->
    <div id="navbar-container">
        <?php 
            if (isset($_COOKIE['manager_account_info'])) {
                include("../sharedcode/manager_nav.php");
            }
            else
            {
                include("../sharedcode/nav.php"); 
            }
        ?>
    </div>

    <?php
        define("IN_CODE", 1);
        include("../_php/dbconfig.php");
        try {
            $con = new mysqli($dbserver, $dbuser, $dbpass, $dbname) or die("<br>Cannot connect to DB.\n");

            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            // check for get parameters
            if (!isset($_GET['product_id'])) {
                die("No product ID provided.");
            }

            $product_id = $_GET['product_id'];

            // product info query
            $query = "SELECT p.id, p.name, p.description, c.category, p.price, p.quantity, p.created 
                FROM Product p 
                JOIN Category c ON (p.category_id = c.id) 
                WHERE p.id = ?;";

            $stmt = $con->prepare($query);

            if (!$stmt) {
                die("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }
            
            $stmt->bind_param('i', $product_id);
            
            if (!$stmt->execute()) {
                die("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                die("This product does not exist.");
            }

            $product_data = array();
            $product = mysqli_fetch_array($result);
            $product_data["id"] = $product['id'];
            $product_data["name"] = $product['name'];
            $product_data["description"] = $product['description'];
            $product_data["category"] = $product['category'];
            $product_data["price"] = $product['price'];
            $product_data["quantity"] = $product['quantity'];
            $product_data["created"] = $product['created'];

            //mysqli_close($con);

            // product color query
            $query = "SELECT c.id, c.color 
                FROM Product_Color pc 
                JOIN Color c ON (pc.color_id = c.id) 
                WHERE pc.product_id = ?;";

            $stmt = $con->prepare($query);
            
            if (!$stmt) {
                die("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }

            $stmt->bind_param('i', $product_id);
            
            if (!$stmt->execute()) {
                die("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                die("Product has zero colors.");
            }

            $color_array = array();

            while ($row = mysqli_fetch_array($result)) {
                array_push($color_array, array("id" => $row['id'], "color" => $row['color']));
            }
            $product_data["colors"] = $color_array;

            //mysqli_close($con);

            // product size query
            $query = "SELECT s.id, s.size 
                FROM Product_Size ps 
                JOIN Size s ON (ps.size_id = s.id) 
                WHERE ps.product_id = ?;";
            $stmt = $con->prepare($query);
            
            if (!$stmt) {
                die("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }
            
            $stmt->bind_param('i', $product_id);

            if (!$stmt->execute()) {
                die("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                die("Product has zero sizes.");
            }

            $size_array = array();

            while ($row = mysqli_fetch_array($result)) {
                array_push($size_array, array("id" => $row['id'], "size" => $row['size']));
            }
            $product_data["sizes"] = $size_array;

            //mysqli_close($con);

            // product images query
            $query = "SELECT pi.id, pi.image_og, pi.image_pp, pi.color_id
                FROM Product_Image pi 
                WHERE pi.product_id = ?;";
            $stmt = $con->prepare($query);
            
            if (!$stmt) {
                die("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }
            
            $stmt->bind_param('i', $product_id);

            if (!$stmt->execute()) {
                die("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                die("Product has zero sizes.");
            }

            $image_array = array();

            while ($row = mysqli_fetch_array($result)) {
                array_push($image_array, array("id" => $row['id'], "image_og" => $row['image_og'], "image_pp" => $row['image_pp'], "color_id" => $row['color_id']));
            }
            $product_data["images"] = $image_array;

            mysqli_free_result($result);
        }
        catch (Exception $e) {
            // This will catch PHP exceptions and return as JSON
            die('Caught exception: ' . $e->getMessage());
        }
    ?>

    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <!-- Loading section-->
            <div class="container" id="loading" style="display:none;">
                <div class="row">
                    <div class="col">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product section-->
            <div class="row gx-4 gx-lg-5 align-items-center" id="product_section">
                <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="<?php echo $product_data['images'][0]['image_og']; ?>" alt="<?php $product_data['name']; ?>" /></div>
                <div class="col-md-6">
                    <div class="small mb-1">ID: <?php echo $product_data['id']; ?></div>
                    <div class="small mb-1">Category: <?php echo $product_data['category']; ?></div>
                    <h1 class="display-5 fw-bolder"><?php echo $product_data['name']; ?></h1>
                    <div class="fs-5 mb-5">
                        <span><?php echo $product_data['price']; ?></span>
                    </div>
                    <p class="lead"><?php echo $product_data['description']; ?></p>
                    <p class="">In stock: <?php echo $product_data['quantity']; ?></p>
                    <form id="add_to_cart">
                        <div class="d-flex">
                            <div class="input-group">
                                <div class="input-group-text">Color</div>
                                <select class="form-select me-3" id="product_color" form="add_to_cart" required>
                                    <option value="0" selected disabled>Select color</option>
                                    <?php
                                        foreach ($product_data['colors'] as $color) {
                                            echo "<option value='" . $color['id'] . "'>" . $color['color'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div><br>
                            <div class="input-group">
                                <div class="input-group-text">Size</div>
                                <select class="form-select me-3" id="product_size" form="add_to_cart" required>
                                    <option value="0" selected disabled>Select size</option>
                                    <?php
                                        foreach ($product_data['sizes'] as $size) {
                                            echo "<option value='" . $size['id'] . "'>" . $size['size'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div><br>
                        <div class="d-flex">
                            <input class="form-control text-center me-3" id="product_quantity" type="number" value="1" max="<?php echo $product_data['quantity']; ?>" style="max-width: 4rem" />
                            <button type="submit" class="btn btn-outline-dark flex-shrink-0 me-3" form="add_to_cart">
                                <i class="bi-cart-fill me-1"></i>
                                Add to cart
                            </button>
                            <?php
                                $in_cart_message = "";
                                if (isset($_COOKIE['cart'])) {
                                    $cart = json_decode($_COOKIE['cart']);
                                    // check if product is already in cart
                                    
                                    foreach ($cart as $item) {
                                        if ($item->id == $product_data['id']) {
                                            $in_cart_message = "This product is in your cart.";
                                            break;
                                        }
                                    }
                                }
                            ?>
                            <div class="note" id="in_cart_message"><?php echo $in_cart_message ?></div>
                        </div>
                        <div class="error" id="error_message"></div>
                        <div class="success" id="success_message"></div>
                        <input type="hidden" id="product_id" value="<?php echo $product_data['id']; ?>">
                        <input type="hidden" id="product_price" value="<?php echo $product_data['price']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Reviews section-->
    <!-- Reviews section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5">
            <h2 class="fw-bolder mb-4">Reviews and Ratings</h2>
            <!-- Review feed -->
            <div class="row gx-4 gx-lg-5">
            <?php

                // Assuming $product_id contains the page's product id
                // Assuming you have a database connection established

                // Prepare the SQL query
                $query = "SELECT * FROM store_template.Review WHERE product_id = ?";

                // Prepare the statement
                $stmt = $con->prepare($query);

                if(!$stmt) {
                    die("Error preparing the statement.\n" . $con->error . "\n");
                }

                // Bind the product_id parameter
                $stmt->bind_param('i', $product_data['id']);

                // Execute the query
                $stmt->execute();

                $result = $stmt->get_result();

                // Fetch all the reviews as an associative array
                $num_reviews = mysqli_num_rows($result);

                echo "This item has $num_reviews reviews.";

                // Iterate over the reviews and display them
                while ($review = mysqli_fetch_array($result)) {
                    echo '<div class="col-md-6 mb-4">';
                    echo '<div class="card h-100">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $review['title'] . '</h5>';
                    echo '<p class="card-text">' . $review['content'] . '</p>';
                    echo '</div>';
                    echo '<div class="card-footer">';
                    echo '<div class="d-flex justify-content-between align-items-center">';
                    echo '<div class="rating">';
                    echo '<span class="star"></span>';
                    echo '<span class="star"></span>';
                    echo '<span class="star"></span>';
                    echo '<span class="star"></span>';
                    echo '<span class="star"></span>';
                    echo '</div>';
                    echo '<small class="text-muted">' . $review['author'] . '</small>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }

                mysqli_close($con);
            ?>
            </div>
            <!-- Review form -->
            <div class="row gx-4 gx-lg-5 mt-4">
                <div class="col-md-6">
                    <h4 class="mb-3">Leave a Review</h4>
                    <form id="leave_review">
                        <div class="mb-3">
                            <label for="reviewTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="reviewTitle" name="reviewTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="reviewContent" class="form-label">Content</label>
                            <textarea class="form-control" id="reviewContent" name="reviewContent" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reviewRating" class="form-label">Rating</label>
                            <select class="form-select" id="reviewRating" name="reviewRating" required>
                                <option value="" selected disabled>Select rating</option>
                                <option value="1">1 star</option>
                                <option value="2">2 stars</option>
                                <option value="3">3 stars</option>
                                <option value="4">4 stars</option>
                                <option value="5">5 stars</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Related items section-->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="fw-bolder mb-4">Related products</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Product image-->
                        <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder">Fancy Product</h5>
                                <!-- Product price-->
                                $40.00 - $80.00
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">View options</a></div>
                        </div>
                    </div>
                </div>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Sale badge-->
                        <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                        <!-- Product image-->
                        <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder">Special Item</h5>
                                <!-- Product reviews-->
                                <div class="d-flex justify-content-center small text-warning mb-2">
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                </div>
                                <!-- Product price-->
                                <span class="text-muted text-decoration-line-through">$20.00</span>
                                $18.00
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                        </div>
                    </div>
                </div>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Sale badge-->
                        <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                        <!-- Product image-->
                        <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder">Sale Item</h5>
                                <!-- Product price-->
                                <span class="text-muted text-decoration-line-through">$50.00</span>
                                $25.00
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                        </div>
                    </div>
                </div>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Product image-->
                        <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder">Popular Item</h5>
                                <!-- Product reviews-->
                                <div class="d-flex justify-content-center small text-warning mb-2">
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                </div>
                                <!-- Product price-->
                                $40.00
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/cart.js"></script>
    <script src="../_js/view_product.js"></script>
</body>
</html>