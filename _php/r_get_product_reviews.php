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

        // Check that the product_id parameter was provided
        if (!isset($_POST['product_id'])) {
            return_json_failure("No product ID provided.");
        }

        $product_id = $_POST['product_id'];

        // Prepare the SQL query
        $query = "
            SELECT 
            r.id, 
            c.first_name, 
            c.last_name, 
            ci.image_og as profile_picture, 
            r.rating, 
            r.review, 
            r.date 
            FROM 
            store_template.Review r 
            LEFT JOIN store_template.Customer c ON (r.customer_id = c.id) 
            LEFT JOIN (
                SELECT 
                customer_id, 
                MIN(id) as min_id 
                FROM 
                store_template.Customer_Image 
                GROUP BY 
                customer_id
            ) as ci_min ON (c.id = ci_min.customer_id)
            LEFT JOIN store_template.Customer_Image ci ON (ci.id = ci_min.min_id)
            WHERE 
            product_id = ?
        ";


        // Prepare the statement
        $stmt = $con->prepare($query);

        if(!$stmt) {
            return_json_error("Error preparing the statement.\n" . $con->error . "\n");
        }

        // Bind the product_id parameter
        $stmt->bind_param('i', $product_id);

        // Execute the query
        $stmt->execute();

        $result = $stmt->get_result();

        // Store total reviews
        $total_reviews = mysqli_num_rows($result);

        $review_data = array();
        $review_data["total_reviews"] = $total_reviews;

        $reviews_array = array();
        // Iterate over the reviews
        while ($review = mysqli_fetch_array($result)) {
            array_push($reviews_array, $review);
        }

        $review_data["reviews"] = $reviews_array;

        // get average rating of reviews
        $query = "SELECT AVG(rating) as average_rating FROM store_template.Review WHERE product_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $average_rating = mysqli_fetch_array($result)['average_rating'];

        $review_data["average_rating"] = $average_rating;

        $review_data = json_encode($review_data);

        mysqli_free_result($result);
        mysqli_close($con);     
        // Return the reviews as JSON
        return_json_success($review_data);
    }
    catch (Exception $e) {
        // This will catch PHP exceptions and return as JSON
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>
