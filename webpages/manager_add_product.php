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

            if (mysqli_num_rows($result) > 0) {
                $count = 1;
                while($row = mysqli_fetch_array($result)) {
                    $id = $row['id'];
                    $color = $row['color'];
                    $color_checkboxes = $color_checkboxes . <<<HTML
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="color$id" value="$id" name="colors[]">
                            <label class="form-check-label" for="color$id">$color</label>
                        </div>
                    HTML;
                    if ($count % 4 == 0) {
                        $color_checkboxes = $color_checkboxes . "<br>";
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
    <!-- 
        create table Product (
            id int primary key not null auto_increment,
            name varchar(255) not null,
            description varchar(255) not null,
            price decimal(10,2) not null,
            quantity int not null
        );


        create table Product_Image (
            id int primary key not null auto_increment,
            product_id int not null,
            image_og blob not null,
            image_pp blob not null,
            foreign key (product_id) references Product(id)
        );
    -->

    <div class="text-center">
        <h2><b>Add New Product</b></h2><br>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="form-control" id="create_manager_account_form">
                        <form id="admin_create_manager_account" enctype="multipart/form-data"> <!-- requires php file -->
                            <label class="form-label" for="name"><b>Product Name</b></label><br>
                            <input class="form-control" type="text" id="name" name="name" required><br>
                            <label class="form-label" for="description"><b>Description</b></label><br>
                            <textarea class="form-control" rows="4" id="description" name="description" required></textarea><br>
                            <div class="input-group">
                                <div class="input-group-text">Sell Price ($)</div>
                                <input type="number" class="form-control" placeholder="0.00" name="sell_price" required>
                            </div><br>
                            <div class="input-group">
                                <div class="input-group-text">Quantity in stock</div>
                                <input type="number" class="form-control" placeholder=0 name="quantity" min='0' step='1' required>
                            </div>
                            <br>
                            <div class="input-group">
                                <label class="input-group-text" for="category">Category</label><br>
                                <select class="form-control" id="category" name="category" required>
                                    <?php echo $category_select_options; ?>
                                </select>
                            </div>
                            <br>
                            <label class="form-label" for="colors"><b>Colors</b></label><br>
                            <div id="colors">
                                <?php echo $color_checkboxes; ?>
                            </div>
                            <br>
                            <label class="form-label" for="sizes"><b>Sizes</b></label><br>
                            <div id="sizes">
                                <?php echo $size_checkboxes; ?>
                            </div>
                            <br>
                            <input class="form-control btn btn-success" type="submit" value="Submit New Product">
                        </form>
                        <div id="error_message"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="success_message"></div>
    </div>
    
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/admin.js"></script>
</body>
</html>