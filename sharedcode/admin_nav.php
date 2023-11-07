<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="admin.php">The Booth - Admin Portal</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
            <li class="nav-item"><a class="nav-link" href="admin.php">Account Management</a></li>
        </ul>
        <div>
            <?php 
                $account_info = json_decode($_COOKIE['admin_account_info'], true);
                $first_name = $account_info['first_name'];
                $last_name = $account_info['last_name'];
                echo "ADMIN ACCOUNT: " . $first_name . " " . $last_name;
            ?>
            <i class="bi bi-person-circle"></i>
            <a href="logout.php" class="btn btn-danger" style="margin-left:10px;">
                Logout
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>