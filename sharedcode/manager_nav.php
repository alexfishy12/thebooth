<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="main_page.php">The Booth Demo Site</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link" href="../index.html">About</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">All Products</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Manager Actions</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="manager_add_product.php">Add New Product</a></li>
                        <li><a class="dropdown-item" href="manager_order_history.php">View Order History</a></li>
                    </ul>
                </li>
            </ul>
            <?php 
                $account_info = json_decode($_COOKIE['manager_account_info'], true);
                $first_name = $account_info['first_name'];
                $last_name = $account_info['last_name'];
                echo <<<HTML
                    <div class="text-decoration-none" style="margin-right:10px;">
                        MANAGER ACCOUNT: $first_name $last_name
                        <i class="bi bi-person-circle"></i>
                    </div>
                HTML;
            ?>
            <a href="logout.php" class="btn btn-danger" style="margin-left:10px;">
                Logout
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>