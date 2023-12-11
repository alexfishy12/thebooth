<?php
    define("IN_CODE", 1);
    include("dbconfig.php");
    include("functions.php");
    try {
        $con = new mysqli($dbserver, $dbuser, $dbpass, $dbname) or return_json_error("<br>Cannot connect to DB.\n");

        if ($con->connect_error) {
            return return_json_error("Connection failed: " . $mysqli->connect_error);
            die();
        }

        // general product info query
        $query = "SELECT p.id, p.name, p.description, c.category, p.price, p.quantity, p.created 
            FROM Product p 
            JOIN Category c ON (p.category_id = c.id);";

        $stmt = $con->prepare($query);

        if (!$stmt) {
            return_json_error("Prepared statement failed: (" . $con->errno . ") " . $con->error);
        }       
        if (!$stmt->execute()) {
            return_json_error("Statement Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        $products_result = $stmt->get_result();
        
        if (mysqli_num_rows($products_result) < 1) {
            return_json_failure("No products exist.");
            die();
        }


        $products_array = array();
        if ($products_result) {
            while ($product = mysqli_fetch_array($products_result)) {
                $product_data = array();
                $product_data["id"] = $product['id'];
                $product_data["name"] = $product['name'];
                $product_data["description"] = $product['description'];
                $product_data["category"] = $product['category'];
                $product_data["price"] = $product['price'];
                $product_data["quantity"] = $product['quantity'];
                $product_data["created"] = $product['created'];
    
                $product_id = $product['id'];
    
                 // product color query
                $query = "SELECT c.id, c.color 
                    FROM Product_Color pc 
                    JOIN Color c ON (pc.color_id = c.id) 
                    WHERE pc.product_id = ?;";
    
                $stmt = $con->prepare($query);    
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $color_array = array();
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        array_push($color_array, array("id" => $row['id'], "color" => $row['color']));
                    }
                }
                $product_data["colors"] = $color_array;
    
                //mysqli_close($con);
    
                // product size query
                $query = "SELECT s.id, s.size 
                    FROM Product_Size ps 
                    JOIN Size s ON (ps.size_id = s.id) 
                    WHERE ps.product_id = ?;";
                
                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
    
                $size_array = array();
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            array_push($size_array, array("id" => $row['id'], "size" => $row['size']));
                        }
                    }
                }
                $product_data["sizes"] = $size_array;
    
                //mysqli_close($con);
    
                // product images query
                $query = "SELECT pi.id, pi.image_og, pi.color_id
                    FROM Product_Image pi 
                    WHERE pi.product_id = ?;";

                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
    
                $image_array = array();
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            array_push($image_array, array("id" => $row['id'], "image_og" => $row['image_og'], "color_id" => $row['color_id']));
                        }
                    }
                }

                // product average rating query 
                $query = "SELECT AVG(rating) AS avg_rating FROM store_template.Review WHERE product_id = ?;";
                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = mysqli_fetch_array($result);
                $product_data["avg_rating"] = $row['avg_rating'];


                $product_data["images"] = $image_array;
    
                // done building out product data, push to array
                array_push($products_array, $product_data);
            }
        }

       

        return_json_success($products_array);

        mysqli_free_result($result);
        mysqli_close($con);
    }
    catch (Exception $e) {
        // This will catch PHP exceptions and return as JSON
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>
