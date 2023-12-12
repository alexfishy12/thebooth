<?php
include("dbconfig.php");
$con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

$customer_id = intval(json_decode($_COOKIE['customer_account_info'], true)['id']);

$sql = "SELECT o.id, o.date, o.status, GROUP_CONCAT(p.name SEPARATOR ', ') AS product_names, SUM(p.price * po.quantity) AS total_price
        FROM store_template.Order o 
        JOIN store_template.Product_Order po ON o.id = po.order_id 
        JOIN store_template.Product p ON po.product_id = p.id
        WHERE o.customer_id = '$customer_id'
        GROUP BY o.id, o.date, o.status";

$result = $con->query($sql);

$total_income = 0; // Variable to hold the total income

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total_income += $row["total_price"]; // Accumulate the total price
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["date"] . "</td>
                <td>" . $row["product_names"] . "</td>
                <td>$" . $row["total_price"] . "</td>
                <td>" . $row["status"] . "</td>
              </tr>";
    }
    // After the loop, display the total income
    echo "<tr>
            <td>Orders Total:</td>
            <td></td>
            <td></td>
            <td>$" . $total_income . "</td>
            <td></td>
          </tr>";
} else {
    echo "<tr><td colspan='5'>No orders found</td></tr>";
}
$con->close();
?>
