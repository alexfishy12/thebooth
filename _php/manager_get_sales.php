<?php
define("IN_CODE", 1);
include("dbconfig.php");
$con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

$sql = "SELECT o.id, o.date, o.status, SUM(p.price * po.quantity) AS total_price 
        FROM store_template.Order o 
        JOIN store_template.Product_Order po ON o.id = po.order_id 
        JOIN store_template.Product p ON po.product_id = p.id 
        GROUP BY o.id, o.date, o.status";

$result = $con->query($sql);

$total_income = 0; 

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total_income += $row["total_price"];
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["date"] . "</td>
                <td>$" . $row["total_price"] . "</td>
                <td>" . $row["status"] . "</td>
              </tr>";
    }
    echo "<tr>
            <td>Gross Income:</td>
            <td></td>
            <td>$" . $total_income . "</td>
            <td></td>
          </tr>";
} else {
    echo "<tr><td colspan='4'>No orders found</td></tr>";
}
$con->close();
?>