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

<body>
    <!-- Navigation-->
    <div id="navbar-container"><?php include("../sharedcode/nav.php"); ?></div>
    <br>   
    <div class="text-center">
        <h2><b>Customer Login</b></h2><br>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="form-control" id="sign_in_form">
                        <form id="customer_login"> <!-- requires php file to save info to database-->
                            <label class="form-label" for="email">Email:</label><br>
                            <input class="form-control" type="email" id="email" name="email"><br>
                            <label class="form-label" for="password">Password:</label><br>
                            <input class="form-control" type="password" id="password" name="password"><br><br>
                            <input class="form-control btn btn-success" type="submit" value="Login">
                        </form>
                        <br>
                        <span class="error" id="error_message"></span><br><br>
                        Don't have an account? <a href="customer_registration.php">Sign up here!</a>
                        <br><br>
                        <a href="admin_login.php">Admin Login</a>
                        <a href="manager_login.php">Staff Login</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="success_message"></div>
    </div>  
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/account.js"></script>
</body>
</html>