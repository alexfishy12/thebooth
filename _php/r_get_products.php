<?php
    define("IN_CODE", 1);
    include('dbconfig.php');
    include('functions.php');

    $connection = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);

    if (!$connection) {
        return_json_error("Database connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT p.id, p.name, p.description, p.price, p.quantity, c.category, co.color 
        FROM store_template.Product p 
        JOIN store_template.Category c ON p.category_id = c.id 
        JOIN store_template.Product_Color po ON p.id = po.product_id 
        JOIN store_template.Color co ON po.color_id = co.id";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        return_json_error("Get products Query failed: " . mysqli_error($connection));
    }

    $product_data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $product_data[] = $row;
    }

    mysqli_close($connection);

    return_json_success($product_data);
?>
