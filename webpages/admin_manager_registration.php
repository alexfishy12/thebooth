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
        <h2><b>Create Manager Account</b></h2><br>
        <div id="sign_up_form">
            <form id="admin_create_manager_account" enctype="multipart/form-data"> <!-- requires php file -->
                <label for="first_name">First Name:</label><br>
                <input type="text" id="first_name" name="first_name" required><br><br>
                <label for="last_name">Last Name:</label><br>
                <input type="text" id="last_name" name="last_name" required><br><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>
                <input type="submit" value="Create Account">
            </form>
            <div id="error_message"></div>
        </div>
        <div id="success_message"></div>
    </div>
    
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/admin.js"></script>
</body>
</html>