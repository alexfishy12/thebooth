<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="main_page.php">The Booth Demo Site</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <!--
                <li class="nav-item"><a class="nav-link" href="../index.html">About</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">All Products</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li> -->
            </ul>
                <?php 
                    if (isset($_COOKIE['customer_account_info'])) {
                        $account_info = json_decode($_COOKIE['customer_account_info'], true);
                        $first_name = $account_info['first_name'];
                        $last_name = $account_info['last_name'];

                        if (isset($_COOKIE['cart'])) {
                            $cart = json_decode($_COOKIE['cart']);
                            $cart_count = count($cart);
                        }
                        else {
                            $cart_count = 0;
                        }

                        echo <<<HTML
                            <a href="customer_account.php" class="nav-link text-decoration-none text-primary me-3 mb-1">
                                $first_name $last_name
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <form class="d-flex me-3 mb-1">
                                <a class="btn btn-outline-dark" href="customer_cart.php">
                                    <i class="bi-cart-fill me-1"></i>
                                    Cart
                                    <span class="badge bg-dark text-white ms-1 rounded-pill" id='cart_count'>$cart_count</span>
                                </a>
                            </form>
                            <form class="d-flex mb-1">
                                <a href="logout.php" class="btn btn-danger">
                                    Logout
                                    <i class="bi bi-box-arrow-right"></i>
                                </a>
                            </form>
                        HTML;
                    }
                    else
                    {
                        echo <<<HTML
                           <form class="d-flex">
                                <a class="btn btn-primary" href="customer_login.php" >
                                    Login
                                    <i class="bi bi-box-arrow-in-left"></i>
                                </a>
                            </form>
                        HTML;
                    }
                ?>
            
        </div>
    </div>
</nav>