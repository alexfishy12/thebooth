<?php
    setcookie("customer_account_info", "", time() - 3600, '/');
    setcookie("admin_account_info", "", time() - 3600, '/');
    setcookie("manager_account_info", "", time() - 3600, '/');
    header("Location: main_page.php");
?>