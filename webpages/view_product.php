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
                <!-- Product image-->
                <div class="col-md-6">
                    <div class="card-body">
                        <div id="image-carousel" class="carousel slide">
                            <div class="carousel-inner view-product-carousel" id="carousel-inner">
                                <?php
                                if (count($product_data['images']) > 0 && $product_data['images'][0]['image_og'] != "") {
                                    $first = true;
                                    foreach ($product_data['images'] as $image) {
                                        if ($first) {
                                            echo "<div class='carousel-item active' data-bs-interval='10000'>";
                                            $first = false;
                                        }
                                        else {
                                            echo "<div class='carousel-item' data-bs-interval='10000'>";
                                        }
                                        echo "<img src='../__uploads/product_images/" . $product_data['id'] . "/" . $image['image_og'] . "' alt='" . $product_data['name'] . "' />";
                                        echo "</div>";
                                    }
                                }
                                else {
                                    // use placeholder img
                                    switch ($product_data['category']) {
                                        case "shirt":
                                            $img_src = "../_assets/tshirt_placeholder.png";
                                            break;
                                        case "pants":
                                            $img_src = "../_assets/pants_placeholder.png";
                                            break;
                                        case "dress":
                                            $img_src = "../_assets/dress_placeholder.png";
                                            break;
                                        case "jacket":
                                            $img_src = "../_assets/jacket_placeholder.png";
                                            break;
                                        default:
                                            $img_src = "../_assets/tshirt_placeholder.png";
                                            break;
                                    }
                                    echo "<div class='carousel-item active' data-bs-interval='10000'>";
                                    echo "<img src='" . $img_src . "' alt='" . $product_data['name'] . "' />";
                                    echo "</div>";
                                }
                                ?>
                            </div>
                            <?php 
                                if (count($product_data['images']) > 1) {
                                    echo <<<HTML
                                        <button class="btn btn-outline-dark carousel-control-prev" type="button" data-bs-target="#image-carousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="btn btn-outline-dark carousel-control-next" type="button" data-bs-target="#image-carousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    HTML;
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="hidden" id="product_id_reference" value="<?php echo $product_data['id']; ?>"></input>
                    <div class="small mb-1">ID: <?php echo $product_data['id']; ?></div>
                    <div class="small mb-1 note"><?php echo $product_data['category']; ?></div>
                    <h1 class="display-5 fw-bolder"><?php echo $product_data['name']; ?></h1>
                    <div class="fs-5 mb-5">
                        <div>
                            <div class="col text-right">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2 note" id="product_average_rating">
                                            
                                        </div>
                                        <div class="me-3" id="product_average_stars">
                                            
                                        </div>
                                        <div class="note" id="product_review_count">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <div class="d-flex align-items-center">
                            <div class="input-group" style="width: fit-content;">
                                <div class="input-group-text">Quantity</div>
                                <input class="form-control text-center me-3" id="product_quantity" type="number" value="1" max="<?php echo $product_data['quantity']; ?>" style="max-width: 4rem" />
                            </div>
                            <button type="submit" class="btn btn-outline-dark flex-shrink-0 me-3" form="add_to_cart">
                                <i class="bi-cart-fill me-1"></i>
                                Add to cart
                            </button>
                            <?php
                                $in_cart_message = "";
                                if (isset($_COOKIE['customer_account_info'])) {
                                    $account_info = json_decode($_COOKIE['customer_account_info'], true);
                                    if (isset($account_info['cart'])) {
                                        $cart = $account_info['cart'];
                                        // check if product is already in cart
                                        foreach ($cart as $item) {
                                            if ($item['id'] == $product_data['id']) {
                                                $in_cart_message = "This product is in your cart.";
                                                break;
                                            }
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
            <!-- Review form -->
            <div class="row gx-4 gx-lg-5 mt-4">
                <div class="col-md-6">
                    <h4 class="mb-3">Leave your own review</h4>
                    <form id="leave_review">
                        <div class="mb-3">
                            <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="star_rating" class="form-label">Rating</label>
                            <div id="star_rating">
                                <span class="bi-star clickable" id="star_rating_1"></span>
                                <span class="bi-star clickable" id="star_rating_2"></span>
                                <span class="bi-star clickable" id="star_rating_3"></span>
                                <span class="bi-star clickable" id="star_rating_4"></span>
                                <span class="bi-star clickable" id="star_rating_5"></span>
                            </div>
                        </div>
                        <input type=hidden id="review_product_id" name="product_id" value="<?php echo $product_data['id']; ?>"></input>
                        <input type=hidden id="review_rating" name="rating" value="0"></input>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                        <br><br>
                        <div class="error" id="review_error_message"></div>
                        <div class="" id="review_success_message">
                        </div>
                    </form>
                </div>
                <div class="col-md-6 ">
                    <div class="d-flex mb-2">
                        <div class="row w-100 align-items-center">
                            <div class="d-flex col align-items-center">
                                <h4 class="mb-3">Customer Reviews</h4>
                            </div>
                            <div class="col text-right">
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2 note ms-auto" id="reviews_average_rating">
                                            
                                        </div>
                                        <div id="reviews_average_stars">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-auto note" id="reviews_total_count">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row scrollable-feed" id="review_feed">
                            <!-- Review feed -->
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Related items section-->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-2">
            <h2 class="fw-bolder mb-4">Try on Booth</h2>
            <?php 
                // check if customer is logged in
                if (!isset($_COOKIE['customer_account_info'])) {
                    echo "Log in as a customer to use this feature!";
                }
                else {
                    $form_hidden_inputs = "";
                    $product_id = $product_data['id'];
                    $product_carousel_html = "";

                    // build product carousel
                    if (count($product_data['images']) > 0 && $product_data['images'][0]['image_og'] != "") {
                        $first = true;
                        foreach ($product_data['images'] as $image) {
                            if ($first) {
                                $product_carousel_html .= "<div class='carousel-item active' data-bs-interval='10000'>";
                                $first = false;
                                // add hidden inputs for product images

                                $form_hidden_inputs .= "<input type='hidden' name='product_image' value='" . $image['image_og']. "' id='input_product_image'>";
                            }
                            else {
                                $product_carousel_html .= "<div class='carousel-item' data-bs-interval='10000'>";
                            }
                            $product_carousel_html .= "<img src='../__uploads/product_images/" . $product_data['id'] . "/" . $image['image_og'] . "' alt='" . $product_data['name'] . "' />";
                            $product_carousel_html .= "</div>";

                        }
                    }
                    $product_select_buttons_html = "";
                    if (count($product_data['images']) > 1) {
                        $product_select_buttons_html .= <<<HTML
                            <button class="btn btn-outline-dark carousel-control-prev" type="button" data-bs-target="#product_image_carousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="btn btn-outline-dark carousel-control-next" type="button" data-bs-target="#product_image_carousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        HTML;
                    }

                    $account_info = json_decode($_COOKIE['customer_account_info'], true);
                    $customer_id = $account_info['id'];

                    $query = "SELECT * from Customer_Image where customer_id = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('i', $customer_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // build customer carousel
                    $first = true;
                    $customer_carousel_html = "";
                    while ($row = mysqli_fetch_array($result)) {
                        if ($first) {
                            $customer_carousel_html .= "<div class='carousel-item active' data-bs-interval='10000'>";
                            $first = false;
                            $form_hidden_inputs .= "<input type='hidden' name='customer_image' value='" . $row['image_og']. "' id='input_customer_image'>";
                        }
                        else {
                            $customer_carousel_html .= "<div class='carousel-item' data-bs-interval='10000'>";
                        }
                        $customer_carousel_html .= "<img src='../__uploads/customer_images/" . $customer_id . "/" . $row['image_og'] . "' alt='Profile Picture' />";
                        $customer_carousel_html .= "</div>";
                    }

                    $customer_select_buttons_html = "";
                    if (mysqli_num_rows($result) > 1) {
                        $customer_select_buttons_html .= <<<HTML
                            <button class="btn btn-outline-dark carousel-control-prev" type="button" data-bs-target="#customer_image_carousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="btn btn-outline-dark carousel-control-next" type="button" data-bs-target="#customer_image_carousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        HTML;
                    }

                    echo <<<HTML
                         <div class="row justify-content-center">
                            <div class="card mb-3 p-0" id="">
                                <div class="card-header">
                                    <h5 class="card-title">Select two images to combine!</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col text-center">
                                            <h5>Product Image</h5>
                                            <div id="product_image_carousel" class="carousel slide">
                                                <div class="carousel-inner booth-carousel" id="carousel-inner">
                                                    $product_carousel_html
                                                </div>
                                                $product_select_buttons_html
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                                            <h1>+</h1>
                                        </div>
                                        <div class="col text-center">
                                            <h5>Customer Image</h5>
                                            <div id="customer_image_carousel" class="carousel slide">
                                                <div class="carousel-inner booth-carousel" id="carousel-inner">
                                                    $customer_carousel_html
                                                </div>
                                                $customer_select_buttons_html
                                            </div>
                                        </div>
                                        <form id="try_on_booth">
                                            <input type="hidden" name="customer_id" value="$customer_id" id="input_customer_id">
                                            <input type="hidden" name="product_id" value="$product_id" id="input_product_id">
                                            $form_hidden_inputs
                                        </form>
                                    </div>                       
                                </div>
                                <hr>
                                <div class="card-header text-center justify-content-center" style="">
                                    <div id="generate_button">
                                        <button type="submit" class="btn btn-success mt-5 mb-5" id="generate_booth_image" form="try_on_booth">Generate!</button>
                                    </div>
                                    <div class='mt-3 mb-5' id="reset_booth" style="display:none">
                                        <button class="btn btn-warning" onclick="reset_booth()">Reset</button>
                                    </div>
                                    <div class='mb-3' id="booth_image" style="display:none">
                                    </div>
                                    <div class="mb-3" id="booth_error_message" style='display:none'>
                                    </div>
                                    <div class='mt-3' id="booth_image_spinner" style="display:none">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <h3>Image generating... please be patient</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    HTML;
                }
            ?>
        </div>
    </section>
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/cart.js"></script>
    <script src="../_js/reviews.js"></script>
    <script src="../_js/view_product.js"></script>
    <script src="../_js/try_on_booth.js"></script>
</body>
</html>