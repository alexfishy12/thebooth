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
    <div id="navbar-container"><?php include("../sharedcode/manager_nav.php"); ?></div>
    <br>
    <?php 
        if (!isset($_COOKIE['manager_account_info'])) {
            header("Location: manager_login.php");
            die();
        }

        define("IN_CODE", 1);
        include("../_php/dbconfig.php");
        include("../_php/functions.php");
        try {
            $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or return_json_error("<br>Cannot connect to DB.\n");

            $query = "SELECT id, category FROM store_template.Category;";

            $result = mysqli_query($con, $query);
            
            $category_select_options = "";

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result)) {
                    $id = $row['id'];
                    $category = $row['category'];
                    $category_select_options = $category_select_options . "<option value='$id'>$category</option>";
                }
            }
            else {
                $category_select_options = "<option value='1' disabled>No categories</option>";
            }

            $query = "SELECT id, color FROM store_template.Color;";
            $result = mysqli_query($con, $query);

            $color_checkboxes = "";
            $file_inputs_for_color = "";

            if (mysqli_num_rows($result) > 0) {
                $count = 1;
                while($row = mysqli_fetch_array($result)) {
                    $id = $row['id'];
                    $color = $row['color'];
                    // uppercase first letter in word
                    $color = ucfirst($color);
                    $color_checkboxes = $color_checkboxes . <<<HTML
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="color_$color" value="$id" name="colors[]">
                            <label class="form-check-label" for="color$color">$color</label>
                        </div>
                    HTML;
                    $file_inputs_for_color = $file_inputs_for_color . <<<HTML
                        <div class="col">
                            <div class="card" id="file_input_$id" style="display:none">
                                <div class="card-header">
                                    $color product images
                                </div>
                                <div class="card-body">
                                    <input type="file" class="form-control" id="image_input" name=images[$id] acccept="image/*" form="manager_add_new_product" multiple>
                                </div>
                            </div>
                        </div>
                    HTML;
                    if ($count % 3 == 0) {
                        $color_checkboxes = $color_checkboxes . "<br>";
                    }
                    if ($count % 3 == 0) {
                        $file_inputs_for_color = $file_inputs_for_color . "</div><br><div class='row'>";
                    }
                    $count++;
                }
            }

            $query = "SELECT id, size FROM store_template.Size;";
            $result = mysqli_query($con, $query);

            $size_checkboxes = "";

            if (mysqli_num_rows($result) > 0) {
                $count = 1;
                while($row = mysqli_fetch_array($result)) {
                    $id = $row['id'];
                    $size = $row['size'];
                    $size_checkboxes = $size_checkboxes . <<<HTML
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="size$id" value="$id" name="sizes[]">
                            <label class="form-check-label" for="size$id">$size</label>
                        </div>
                    HTML;
                    if ($count % 4 == 0) {
                        $size_checkboxes = $size_checkboxes . "<br>";
                    }
                    $count++;
                }
            }
        }
        catch (Exception $e) {
            // This will catch PHP exceptions and return as JSON
            return_json_error('Caught exception: ' . $e->getMessage());
        }
    ?>

    <div class="text-center">
        <h2><b>Add New Product</b></h2><br>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col">
                    <div class="form-control" id="manager_add_new_product_form">
                        <form id="manager_add_new_product" enctype="multipart/form-data"> <!-- requires php file -->
                            <div class="row">
                                <div class="col">
                                    <label class="form-label" for="name"><b>Product Name</b></label><br>
                                    <input class="form-control" type="text" id="name" name="name" required><br>
                                    <label class="form-label" for="description"><b>Description</b></label><br>
                                    <textarea class="form-control" rows="4" id="description" name="description" required></textarea><br>
                                    <div class="input-group">
                                        <div class="input-group-text">Sell Price ($)</div>
                                        <input type="number" class="form-control" placeholder="0.00" name="sell_price" step='0.01' required>
                                    </div><br>
                                    <div class="input-group">
                                        <div class="input-group-text">Quantity</div>
                                        <input type="number" class="form-control" placeholder=0 name="quantity" min='0' step='1' required>
                                    </div><br>
                                </div>
                                <div class="col mt-2">
                                    <label class="form-label" for="category_group"></label>
                                    <div class="input-group" id="category_group">
                                        <label class="input-group-text" for="category">Category</label><br>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="" selected disabled hidden>Choose an option</option>
                                            <?php echo $category_select_options; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="card">
                                        <div class="card-header">
                                            Colors
                                        </div>
                                        <div class="card-body">
                                            <div id="colors">
                                                <?php echo $color_checkboxes; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="card">
                                        <div class="card-header">
                                            Sizes
                                        </div>
                                        <div class="card-body">
                                            <div id="sizes">
                                                <?php echo $size_checkboxes; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mt-2">
                                    <br>
                                    <!-- Bootstrap 5 Carousel -->
                                    <div class="card" id="uploaded_images_card" style="">
                                        <div class="card-header">
                                            Uploaded images
                                        </div>
                                        <div class="card-body">
                                            <div id="image-carousel" class="carousel slide">
                                                <div class="carousel-inner" id="carousel-inner">
                                                    None (at least 1 is required)
                                                </div>
                                                <button class="btn btn-outline-dark carousel-control-prev" type="button" data-bs-target="#image-carousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="btn btn-outline-dark carousel-control-next" type="button" data-bs-target="#image-carousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="" id='file_upload_div'>
                                    <!-- product image upload, can upload more than one image -->

                                    </div>
                                </div>
                            </div>
                            <br>
                            <input class="form-control btn btn-success" type="submit" value="Submit New Product">
                        </form>
                        <div class="error" id="error_message"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="success_message"></div>
    </div>
    
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/admin.js"></script>
    <script src="../_js/manager_add_product.js"></script>
</body>
</html>