<!DOCTYPE html>
<html>
<head>
    <title>The Booth</title>
    <!-- Set charset and viewport -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Load bootstrap icons and stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../sharedcode/styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navigation-->
    <div id="navbar-container"><?php include("../sharedcode/nav.php"); ?></div>
    
    <!--Header -->
    <div class="container px-4 px-lg-5 mt-5">
        <div class="text-center"> 
            <h1 class="display-4 fw-bolder">Customer Account Info</h1>
        </div>
        <?php 
            if (!isset($_COOKIE['customer_account_info'])) {
                header("Location: customer_login.php");
                die();
            }
            $account_info = json_decode($_COOKIE['customer_account_info'], true);
            $id = $account_info['id'];
            $first_name = $account_info['first_name'];
            $last_name = $account_info['last_name'];
            $email = $account_info['email'];
            $address = $account_info['address'];
            $city = $account_info['city'];
            $state = $account_info['state'];
            $zip = $account_info['zip'];
            $created = $account_info['created'];

            define("IN_CODE", 1);
            include("../_php/dbconfig.php");

            $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die("Connection Failed");

            $query = "SELECT * from Customer_Image where customer_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = mysqli_fetch_array($result);
            $image_name = $row['image_og'];

            $image_src = "../__uploads/customer_images/$id/$image_name";
            echo <<<HTML
                <div class="text-center">
                    <h2><b>$first_name $last_name</b></h2>
                    <h4>$email</h4>
                    <h4>$address</h4>
                    <h4>$city, $state $zip</h4>
                    <h4>Account created: $created</h4>
                    <img src="$image_src" alt="Profile Picture" height="200">
                </div>
            HTML;
        ?>
    </div>
    <!-- Table -->
<div class="container px-2 px-lg-3 mt-5">
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <table border="1" id="order-table">
            <tr>
                <th>Order Number</th>
                <th>Order Date</th>
                <th>Items</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            <?php include("../_php/customer_get_sales.php"); ?>
        </table>
    </div>        
</div>
<!-- Scripts -->
<script src="../sharedcode/scripts.js"></script>
</body>
</html>