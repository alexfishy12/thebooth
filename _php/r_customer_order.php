<?php 
    // attempt to connect to DB
    define("IN_CODE", 1);
    include("dbconfig.php");
    include("functions.php");
    $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

    // check if user cookie is logged in
    if (!isset($_COOKIE['customer_account_info'])) {
        return_json_failure("You are not logged in. Please log in to place an order.");
    }

    $customer_id = json_decode($_COOKIE['customer_account_info'])['id'];

    // CHECK THAT ALL FORM VARIABLES ARE SET //////////////////////////////////////////////////////
    $variable_not_set = false;
    $error_string = "";
    if (!isset($_POST['product_id'])) {
        $error_string = $error_string . "Form submit error: Product ID not received.<br>";
        $variable_not_set = true;
    }
    if (!isset($_POST['order_quantity'])) {
        $error_string = $error_string . ">Form submit error: Order Quantity not received.<br>";
        $variable_not_set = true;
    }
    if (!isset($_POST['color_id'])) {
        $error_string = $error_string . ">Form submit error: Color ID not received.<br>";
        $variable_not_set = true;
    }
    if (!isset($_POST['size_id'])) {
        $error_string = $error_string . ">Form submit error: Size ID not received.<br>";
        $variable_not_set = true;
    }

    // if any of the variables weren't set, kill program
    if ($variable_not_set) {
        return_json_error($error_string);
    }

    $num_ordered_products = 0;
    $products_ordered = array();
    $order_errors = array();

    // get product_id from form
    $id = $_POST['product_id'];
    $num_products_in_list = count($id);

    // get items that the customer is ordering
    for ($i = 0; $i < $num_products_in_list; $i++) {

        $order_quantity = $_POST['order_quantity'][$i];
        if ($order_quantity < 1) {
            continue;
        }
        
        // get variables from form
        $id = $_POST['product_id'][$i];
        $color_id = $_POST['color_id'][$i];
        $size_id = $_POST['size_id'][$i];

        array_push($products_ordered, array("id" => $id, "order_quantity" => $order_quantity, "color_id" => $color_id, "size_id" => $size_id));
    }

    if (count($products_ordered) == 0) {
        print_json_error("No products were set with a quantity greater than 0 to be ordered.");
    }

    // variable to track if order failed
    $order_failed = false;
    // check to see that each item is available to be ordered
    for ($i = 0; $i < count($products_ordered); $i++) {
        // get variables from form
        $id = $products_ordered[$i]['id'];
        $order_quantity = $products_ordered[$i]['order_quantity'];

        // CHECK TO SEE IF PRODUCT IS ABLE TO BE PURCHASED
        $query = "SELECT quantity >= ? as has_enough_quantity FROM store_template.Product where id = ?;";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $order_quantity, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) == 0) {
            // PRODUCT DOESN'T EXIST, ORDER FAILED, CONTINUE TO NEXT PRODUCT FOR MORE ERROR FINDING
            $order_failed = true;
            array_push($order_errors, array("product_id" => $id, "msg" => "Product not found."));
            continue;
        }
        $product = mysqli_fetch_array($result);
        if (!$product['has_enough_quantity']) {
            // PRODUCT DOESN'T HAVE ENOUGH QUANTITY, ORDER FAILED, CONTINUE TO NEXT PRODUCT FOR MORE ERROR FINDING
            $order_failed = true;
            array_push($order_errors, array("product_id" => $id, "msg" => "Not enough quantity."));
        }
    }

    // if order failed, print error and die
    if ($order_failed) {
        return_json_failure($order_errors);
        die();
    }

    // ORDER IS POSSIBLE, UPDATE DATABASE //////////////////////////////////////////////////////
    
    // BEGIN TRANSACTION FOR CONCURRENCY CONTROL
    try {
        $con->begin_transaction();

        // 1) Deduct quantity from PRODUCT table

        for ($i = 0; $i < count($products_ordered); $i++) {
            // get variables from form
            $id = $products_ordered[$i]['id'];
            $order_quantity = $products_ordered[$i]['order_quantity'];

            // SELECT RECORD FOR UPDATE (CONCURRENCY CONTROL)
            $select_query = "SELECT * FROM store_template.Product WHERE id = ? FOR UPDATE;";
            $stmt = $con->prepare($select_query);
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) {
                $con->rollback();
                return_json_error("CONCURRENCY ERROR: Unable to select product #$id for update.");
            }
            $stmt->close();

            // UPDATE ITEM QUANTITY IN DATABASE
            $query = "UPDATE store_template.Product SET quantity = (quantity - ?) WHERE id = ?;";
            $stmt = $con->prepare($query);
            $stmt->bind_param('ii', $order_quantity, $id);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                $con->rollback();
                return_json_error("0 affected rows during Product table quantity update for product #$id.");
            }
        }

        // 2) Insert order information into ORDER table

        $query = "INSERT INTO store_template.Order (customer_id, date, status) VALUES (?, NOW(), 'completed');";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            $con->rollback();
            return_json_error("0 affected rows during ORDER table insert.");
        }

        // 3) Get order_id from ORDER table, (last_submit_id), then insert each product from order into PRODUCT_ORDER table
        
        $order_id = $con->insert_id;
        
        // begin query with first product
        $id = $products_ordered[0]['id'];
        $order_quantity = $products_ordered[0]['order_quantity'];
        $color_id = $products_ordered[0]['color_id'];
        $size_id = $products_ordered[0]['size_id'];

        $query = "INSERT INTO store_template.Product_Order (order_id, product_id, color_id, size_id, quantity) VALUES ($order_id, $id, $color_id, $size_id, $order_quantity)";
        for ($i = 1; $i < count($products_ordered); $i++) {
            // get variables from form
            $id = $products_ordered[$i]['id'];
            $order_quantity = $products_ordered[$i]['order_quantity'];
            $color_id = $products_ordered[$i]['color_id'];
            $size_id = $products_ordered[$i]['size_id'];

            // INSERT EACH PRODUCT INTO PRODUCT_ORDER TABLE
            $query = $query. ", ($order_id, $id, $color_id, $size_id, $order_quantity)";
        }
        $query = $query . ";";
        $result = mysqli_query($con, $query);

        if(!$result) {
            $con->rollback();
            return_json_error("Query failed: " . mysqli_error($con));
        }

        if (mysqli_affected_rows($con) == 0) {
            $con->rollback();
            return_json_error("0 affected rows during Product_Order table insert.");
        }
    }
    catch (Exception $e) {
        // If there's an error, roll back the transaction
        $con->rollback();
        // Handle the error, e.g., by showing a message to the user
        return_json_error("Transaction failed: ". $e->getMessage());
    }
    // If no error, commit the transaction
    $con->commit();

    // ORDER COMPLETE, PRINT OUT ORDER INFORMATION //////////////////////////////////////////////////////
    return_json_success("Order successfully placed.");
?>