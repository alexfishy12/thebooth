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
    <!-- Customer Support Message BoX-->
    <div class="container px-4 px-lg-5 mt-5">
        <div class="text-center"> 
            <h1 class="display-4 fw-bolder">Contact Customer Support</h1>
            <textarea id="freeform" name="freeform" rows="6" cols="50">Please enter your customer service request here</textarea>
            <br>
            <input type="submit" value="Submit">
        </div>
    </div>

    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
</body>
</html>