<?php 
    // attempt to connect to DB
    define("IN_CODE", 1);
    try {
        include("dbconfig.php");
        include("functions.php");
        $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

        // CHECK THAT ALL FORM VARIABLES ARE SET //////////////////////////////////////////////////////

        /* 
            CREATE TABLE `Review` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `product_id` int(11) NOT NULL,
                `customer_id` int(11) NOT NULL,
                `rating` int(11) NOT NULL,
                `review` varchar(255) DEFAULT NULL,
                `date` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `product_id` (`product_id`),
                KEY `customer_id` (`customer_id`),
                CONSTRAINT `Review_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`),
                CONSTRAINT `Review_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        */


        $variable_not_set = false;
        $error_string = "";
        if (!isset($_POST['product_id'])) {
            $error_string = $error_string . "Form submit error: Product ID not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_COOKIE['customer_account_info'])) {
            $error_string = $error_string . "Form submit error: Customer not logged in.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['rating'])) {
            $error_string = $error_string . "Form submit error: Rating not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['review_text'])) {
            $error_string = $error_string . "Form submit error: Review text not received.\n";
            $variable_not_set = true;
        }

        // if any of the variables weren't set, kill program
        if ($variable_not_set) {
            return_json_error($error_string);
        }

        // get variables from form
        $product_id = intval($_POST['product_id']);
        $customer_id = intval(json_decode($_COOKIE['customer_account_info'], true)['id']);
        $rating = intval($_POST['rating']);
        $review_text = $_POST['review_text'];
        
        // check that all variables are valid
        $variable_not_valid = false;
        $error_string = "";

        if (!is_int($product_id)) {
            $error_string = $error_string . "Insert failed: Product ID must be an integer.<br>";
            $variable_not_valid = true;
        }
        if (!is_int($customer_id)) {
            $error_string = $error_string . "Insert failed: Customer ID must be an integer.<br>";
            $variable_not_valid = true;
        }
        if (!is_int($rating)) {
            $error_string = $error_string . "Insert failed: Rating must be an integer.<br>";
            $variable_not_valid = true;
        }
        else if ($rating < 1 || $rating > 5) {
            $error_string = $error_string . "Insert failed: Rating must be between 1 and 5.<br>";
            $variable_not_valid = true;
        }
        if (strlen($review_text) > 255) {
            $error_string = $error_string . "Insert failed: Review must be less than 255 characters.<br>";
            $variable_not_valid = true;
        }
        if ($variable_not_valid) {
            return_json_failure($error_string);
        }


        // CHECK IF CUSTOMER HAS PURCHASED THIS PRODUCT
        // Prepare statement
        $query = "SELECT * FROM store_template.Order o JOIN store_template.Product_Order po ON (o.id = po.order_id) WHERE o.customer_id = ? AND po.product_id = ?;";
        $stmt = $con->prepare($query);
        
        if (!$stmt) {
            return_json_error("Check purchase failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
        }
        
        $stmt->bind_param('ii', $customer_id, $product_id);

        if (!$stmt->execute()) {
            return_json_error("Check purchase failed (Execute failed): (" . $stmt->errno . ") " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) < 1) {
            //return_json_failure("You must purchase this product before you can review it.");
        }

        // INSERT REVIEW INTO DATABASE //////////////////////////////////////////////////////

        // Prepare statement
        $query = "INSERT INTO store_template.Review (product_id, customer_id, rating, review, date) VALUES (?, ?, ?, ?, NOW());";
        $stmt = $con->prepare($query);
        
        if (!$stmt) {
            return_json_error("Insert failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
        }
        
        $stmt->bind_param('iiis', $product_id, $customer_id, $rating, $review_text);

        if (!$stmt->execute()) {
            return_json_error("Insert failed (Execute failed): (" . $stmt->errno . ") " . $stmt->error);
        }
        
        if ($stmt->affected_rows == 0) {
            return_json_error("Insert failed, 0 affected rows.");
        }

        // SUCCESS //////////////////////////////////////////////////////

        $success_html = <<<HTML
            <div class="d-flex align-items-center">
                <span>Your review has been submitted successfully! Reloading page...</span>
                <div class="flex-grow-1"></div> <!-- This div will push the spinner to the right -->
                <div>
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="flex-grow-1"></div> <!-- This div will push the spinner to the left -->
            </div>
        HTML;
        
        return_json_success($success_html);
        
        $stmt->close();
    }
    catch (Exception $e) {
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>