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
    if (!isset($_COOKIE['admin_account_info'])) {
        header("Location: admin_login.php");
        die();
    }
?>
<body>
    <!-- Navigation-->
    <div id="navbar-container"><?php include("../sharedcode/admin_nav.php"); ?></div>
    <br>
    <div class="text-center">
        <h2><b>Account Management</b></h2><br>
        <h3><u>Employee Accounts</u></h3><br>
        <div id="manager_accounts" style="margin-left:15%; margin-right:15%;">Display employee accounts here</div><br>
        <a class="btn btn-outline-dark" href="admin_manager_registration.php">
            Create employee account
            <i class="bi bi-person-plus-fill" style="margin-left:5px"></i>
        </a>
    </div>

    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/admin.js"></script>
</body>
</html>