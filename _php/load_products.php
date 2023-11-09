<?php
    define("IN_CODE", 1);
    include('dbconfig.php');
    $connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

$query = "SELECT p.id, p.name, p.description, p.price, p.quantity, c.category, co.color FROM Product p JOIN Product_Category pc ON p.id = pc.product_id JOIN Category c ON pc.category_id = c.id JOIN Product_Color po ON p.id = po.product_id JOIN Color co ON po.color_id = co.id";
$result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    $product_data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $product_data[] = $row;
    }

    mysqli_close($connection);

    echo json_encode($product_data);
?>
