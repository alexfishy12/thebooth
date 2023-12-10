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
    <div class="text-center" style="margin-left:15%; margin-right:15%;">
        <h2><b>Your cart</b></h2><br>
        
        <div id="cart_list">
            Display cart contents here
        </div>
        <h2 id="cart_subtotal"></h2>
        <br>
        <button class="btn btn-danger" id="button_clear_cart">
            Empty your cart
            <i class="bi bi-cart" style="margin-left:5px"></i>
        </button>
        <button class="btn btn-primary" id="button_checkout">
            Checkout
            <i class="bi bi-cart" style="margin-left:5px"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/cart.js"></script>
    <script src="../_js/cart_page.js"></script>
</body>
</html>