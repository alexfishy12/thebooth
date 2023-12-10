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
    <link href="../sharedcode/custom_styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<?php 
    if (!isset($_COOKIE['customer_account_info'])) {
        header("Location: customer_login.php");
        die();
    }

    include("../_php/f_get_product.php");
?>
<body>
    <!-- Navigation-->
    <div id="navbar-container"><?php include("../sharedcode/nav.php"); ?></div>
    <br>
    <h2 class="text-center" id="section_title"><b>Checkout</b></h2><br>
    <div class="text-center" style="margin-left:15%; margin-right:15%;padding-bottom:5%;">
        <div id="checkout_information">
            <div class="row">
                <div class="col-auto me-5">
                    <h4>Billing/Shipping Information</h4>
                    <?php
                        // Get customer info
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
    
                        echo <<<HTML
                            <div class="text-start">
                                <b>Address</b><br>
                                <table border=0 style="width:100%">
                                    <tr>
                                        <td>Name:</td>
                                        <td class="text-end">$first_name $last_name</td>
                                    </tr>
                                    <tr>
                                        <td>Address:</td>
                                        <td class="text-end">$address</td>
                                    </tr>
                                    <tr>
                                        <td>City/state/zip:</td>
                                        <td class="text-end">$city, $state $zip</td>
                                    </tr>
                                </table>
                            </div>
                        HTML;
                    ?>
                </div>
                <div class="col">
                    <form id="place_order">
                        <h4>Items to purchase</h4>
                        <div id="checkout_list">
                            Display checkout contents here
                        </div>
                    </form>
                    <div class="text-end me-3">
                        <h3 id="cart_subtotal"></h3>
                    </div>
                </div>
            </div>
            <br>
            <button class="btn btn-danger" id="button_cancel_checkout" href="customer_cart.php" onclick="window.location.replace('customer_cart.php');">
                Cancel Checkout
                <i class="bi bi-x-circle" style="margin-left:5px"></i>
            </button>
            <button type="submit" class="btn btn-primary" id="button_place_order" form="place_order">
                Place Order
                <i class="bi bi-bag" style="margin-left:5px"></i>
            </button>
        </div>
        <div id="place_order_spinner" style="display:none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h3>Placing order...</h3>
        </div>
        <div id="success_message"></div>
        <div id="error_screen" style="display:none;">
            <div class="error" id="error_message"></div><br>
            <button class="btn btn-outline-dark" onclick="close_error()">
                Continue
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/cart.js"></script>
    <script src="../_js/checkout.js"></script>
</body>
</html>