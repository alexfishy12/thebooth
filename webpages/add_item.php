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
    <h1>Add New Product</h1>
    <!-- display order status, date, total, user, status -->
    <form action=""> <!-- requires php file to save info to database-->
        <label for="prod_name">Product Name:</label><br>
        <input type="text" id="prod_name" name="prod_name"><br>
        <label for="prod_price">Product Price:</label><br>
        <input type="number" id="prod_price" name="prod_price"><br>
        <label for="prod_pic">Product Image:</label><br>
        <input type="file" id="prod_pic" name="prod_pic"><br>
        <input type="submit" value="Add item">
    </form>

    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
</body>
</html>