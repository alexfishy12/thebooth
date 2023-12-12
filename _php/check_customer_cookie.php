<?php
    include("functions.php");
    if (isset($_COOKIE['customer_account_info'])) {
        $account_info = json_decode($_COOKIE['customer_account_info']);
        $account_info = json_encode($account_info);
        return_json_success($account_info);
    }
    else {
        return_json_failure("No cookie found.");
    }
?>