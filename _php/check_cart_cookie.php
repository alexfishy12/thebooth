<?php
    include("functions.php");
    if (isset($_COOKIE['cart'])) {
        $cart = json_decode($_COOKIE['cart']);
        echo "\nCart amount: " . count($cart) . "\n\n";
        return_json_success($cart);
    }
    else {
        return_json_failure("No cart cookie found.");
    }
?>