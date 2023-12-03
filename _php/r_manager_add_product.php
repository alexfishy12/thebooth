<?php 
    // attempt to connect to DB
    define("IN_CODE", 1);
   //try {
        include("dbconfig.php");
        include("functions.php");
        $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

        // CHECK THAT ALL FORM VARIABLES ARE SET //////////////////////////////////////////////////////

        /*
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
        */

        // product fields
        /* 
            name
            description
            sell_price
            quantity
            category
            colors[]
            sizes[]
            $_FILES['images'] (multiple)
        */


        $variable_not_set = false;
        $error_string = "";
        if (!isset($_POST['name'])) {
            $error_string = $error_string . "Form submit error: Product name not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['description'])) {
            $error_string = $error_string . "Form submit error: Description not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['sell_price'])) {
            $error_string = $error_string . "Form submit error: Sell Price not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['quantity'])) {
            $error_string = $error_string . "Form submit error: Quantity not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['category'])) {
            $error_string = $error_string . "Form submit error: Category not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['colors'])) {
            $error_string = $error_string . "Form submit error: Colors not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['sizes'])) {
            $error_string = $error_string . "Form submit error: Sizes not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_FILES['images'])) {
            $error_string = $error_string . "Form submit error: Images not received.\n";
            $variable_not_set = true;
        }
        if (count($_FILES['images']['name']) < 1) {
            $error_string = $error_string . "Form submit error: No images received.\n";
            $variable_not_set = true;
        } 
        // if any of the variables weren't set, kill program
        if ($variable_not_set) {
            return_json_error($error_string);
        }

        // get variables from form
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category_id = $_POST['category'];
        $price = $_POST['sell_price'];
        $quantity = $_POST['quantity'];
        $colors = $_POST['colors'];
        $sizes = $_POST['sizes'];

        
        // check that all variables are valid
        $variable_not_valid = false;
        $error_string = "";

        if (strlen($name) > 255) {
            $error_string = $error_string . "Insert failed: Product name must be no more than 255 characters.<br>";
            $variable_not_valid = true;
        }
        if (strlen($description) > 255) {
            $error_string = $error_string . "Insert failed: Last name must be no more than 255 characters.<br>";
            $variable_not_valid = true;
        }
        if ($price < 0) {
            $error_string = $error_string . "Insert failed: Price must not be less than \$0.<br>";
            $variable_not_valid = true;
        }
        if ($quantity < 0) {
            $error_string = $error_string . "Insert failed: Quantity must not be less than \$0.<br>";
            $variable_not_valid = true;
        }
        if (count($colors) < 1)
        {
            $error_string = $error_string . "Insert failed: Must select at least one color.<br>";
            $variable_not_valid = true;
        }
        if (count($sizes) < 1)
        {
            $error_string = $error_string . "Insert failed: Must select at least one size.<br>";
            $variable_not_valid = true;
        }
        if ($variable_not_valid) {
            return_json_failure($error_string);
        }

        // CHECK FOR DUPLICATES
        /*
        $query = "SELECT name FROM store_template.Product WHERE name = ?;";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) > 0) {
            return_json_failure("Insert failed: Product name already exists. Please choose a different name.<br>");
        }
        */
        
        // INSERT PRODUCT INTO DATABASE //////////////////////////////////////////////////////

        // 1) Insert product into Product table.
        // Prepare statement
        $query = "INSERT INTO store_template.Product (name, description, category_id, price, quantity, created) VALUES (?, ?, ?, ?, ?, NOW());";
        $stmt = $con->prepare($query);
        if (!$stmt) {
            return_json_error("Insert failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
        }
        
        $stmt->bind_param('ssidi', $name, $description, $category_id, $price, $quantity);
        
        
        if (!$stmt->execute()) {
            return_json_error("Insert failed (Execute failed): (" . $stmt->errno . ") " . $stmt->error);
        }
        
        if ($stmt->affected_rows == 0) {
            return_json_error("Insert into Product table failed, 0 affected rows.");
        }

         // 2) Insert product sizes, colors, and images into Product_Size, Product_Color, and Product_Image tables

        // get product id
        $product_id = $con->insert_id;

        // insert product sizes
        $query = "INSERT INTO store_template.Product_Size (product_id, size_id) VALUES ";
        for ($i = 0; $i < count($sizes); $i++) {
            $query = $query . "($product_id, $sizes[$i])";
            if ($i < count($sizes) - 1) {
                $query = $query . ", ";
            }
        }
        $query = $query . ";";
        $result = mysqli_query($con, $query);

        if(!$result) {
            return_json_error("Insert into Product_Size Query failed: " . mysqli_error($con));
            die();
        }

        // insert product colors
        $query = "INSERT INTO store_template.Product_Color (product_id, color_id) VALUES ";
        for ($i = 0; $i < count($colors); $i++) {
            $query = $query . "($product_id, $colors[$i])";
            if ($i < count($colors) - 1) {
                $query = $query . ", ";
            }
        }

        $query = $query . ";";
        $result = mysqli_query($con, $query);

        if(!$result) {
            return_json_error("Insert into Product_Color Query failed: " . mysqli_error($con));
            die();
        }

        // insert product images



        foreach ($_FILES['images']['name'] as $color_id => $file_name) {

            // if file is empty, break
            if ($_FILES['images']['error'][$color_id] == UPLOAD_ERR_NO_FILE) {
                break;
            }

            //$image_og_blob = file_get_contents($_FILES['images']['tmp_name'][$color_id]);

            $target_dir = "../__uploads/product_images/";
            $target_file_og = $target_dir . $product_id . "_og_$color_id" . basename($_FILES['images']['name'][$color_id]);

            // Attempt to move the OG file
            if (!move_uploaded_file($_FILES['images']['tmp_name'][$color_id], $target_file_og)) {
                return_json_error("Sorry, there was an error uploading your file.");
            }

            // get preprocessed image for ai model
            //$image_pp_blob = get_preprocessed_image($image_og_blob);

            $target_file_pp = $target_dir . $product_id . "_pp_$color_id" . basename($_FILES['images']['name'][$color_id]);
            
            copy($target_file_og, $target_file_pp);
                
            // get preprocessed image for ai model
            
            //$image_pp_blob = get_preprocessed_image($image_og_blob);

            $query = "INSERT INTO store_template.Product_Image (product_id, color_id, image_og, image_pp) VALUES (?, ?, ?, ?);";
            $stmt = $con->prepare($query);

            if (!$stmt) {
                return_json_error("Product_Image insert failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
            }
            
            // These nulls are placeholders for the actual BLOBs
            $null = null;
            $stmt->bind_param("iiss", $product_id, $color_id, $target_file_og, $target_file_pp);

            // Execute the prepared statement
            if (!$stmt->execute()) {
                return_json_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
        }

        // SUCCESS //////////////////////////////////////////////////////

        $success_html = <<<HTML
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3>$name has been created as a product! Redirecting to main page...</h3>
                        <br>
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
        
        return_json_success($success_html);
        
        $stmt->close();
   /*}
    catch (Exception $e) {
        return_json_error('Caught exception: ' . $e->getMessage());
    } 
    */
?>