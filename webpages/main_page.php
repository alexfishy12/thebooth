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
    <div id="navbar-container">
        <?php 
            if (isset($_COOKIE['manager_account_info'])) {
                include("../sharedcode/manager_nav.php");
            }
            else
            {
                include("../sharedcode/nav.php"); 
            }
        ?>
    </div>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">The Booth Demonstration Website</h1>
                <p class="lead fw-normal text-white-50 mb-0">Browse clothing and see how they look on you with our virtual try-on booth!</p>
            </div>
        </div>
    </header>
    <!-- Selection Buttons -->
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center"> 
            <button class="btn btn-outline-dark" id="Shirt">Shirts</button>
            <button class="btn btn-outline-dark" id="Jacket">Jackets</button>
            <button class="btn btn-outline-dark" id="Dress">Dresses</button>
            <button class="btn btn-outline-dark" id="Pants">Pants</button>
        </div>
    </div>
    <!-- Search Bar -->
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <div style="display: flex;">
                <input type="text" id="searchBox" placeholder="Search..." style="flex: 1; margin-right: 10px;">
                <button class="btn btn-outline-dark" id="Search">Search</button>
            </div>
        </div>
    </div>    
    <!-- Items Section -->
    <section id="items" class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="items-row">
            </div>
        </div>
    </section>
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/main_page.js"></script>
</body>
</html>