<?php 
    define("IN_CODE", 1);
    include("dbconfig.php");
    include("functions.php");

     function get_product($product_id) {
        // verify that product_id is an integer
        if (!is_numeric($product_id)) {
            internal_return_json_failure("Product ID must be an integer.");
        }

        $con = new mysqli($dbserver, $dbuser, $dbpass, $dbname) or internal_return_json_error("<br>Cannot connect to DB.\n");

        if ($con->connect_error) {
            internal_return_json_error("Connection failed: " . $mysqli->connect_error);
        }
        try {
            // general product info query
            $query = "SELECT p.id, p.name, p.description, c.category, p.price, p.quantity, p.created 
                FROM Product p 
                JOIN Category c ON (p.category_id = c.id) 
                WHERE p.id = ?;";

            $stmt = $con->prepare($query);

            if (!$stmt) {
                internal_return_json_error("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }
            
            $stmt->bind_param('i', $product_id);
            
            if (!$stmt->execute()) {
                internal_return_json_error("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                internal_return_json_failure("This product does not exist.");
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
                internal_return_json_error("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }

            $stmt->bind_param('i', $product_id);
            
            if (!$stmt->execute()) {
                internal_return_json_error("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                internal_return_json_failure("Product has zero colors.");
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
                internal_return_json_error("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }
            
            $stmt->bind_param('i', $product_id);

            if (!$stmt->execute()) {
                internal_return_json_error("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                internal_return_json_failure("Product has zero sizes.");
                
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
                internal_return_json_error("Prepared statement failed: (" . $con->errno . ") " . $con->error);
            }
            
            $stmt->bind_param('i', $product_id);

            if (!$stmt->execute()) {
                internal_return_json_error("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) < 1) {
                internal_return_json_failure("Product has zero sizes.");
                
            }

            $image_array = array();

            while ($row = mysqli_fetch_array($result)) {
                array_push($image_array, array("id" => $row['id'], "image_og" => $row['image_og'], "image_pp" => $row['image_pp'], "color_id" => $row['color_id']));
            }
            $product_data["images"] = $image_array;

            internal_return_json_success($product_data);

            mysqli_free_result($result);
            mysqli_close($con);
        }
        catch (Exception $e) {
            // This will catch PHP exceptions and return as JSON
            internal_return_json_error('Caught exception: ' . $e->getMessage());
        }
    }
?>