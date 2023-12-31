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

  <!-- display AI image -->
  <section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="..." /></div>
            <div class="col-md-6">
              <div class="dropdown">
                <button class="dropbtn">Color</button>
                <div class="dropdown-content">
                  <a href="#">red</a> <!-- href generates AI image with color -->
                  <a href="#">blue</a> <!-- href generates AI image with color -->
                  <a href="#">green</a> <!-- href generates AI image with color -->
                  <a href="#">black</a> <!-- href generates AI image with color -->
                  <a href="#">purple</a> <!-- href generates AI image with color -->
                </div>
              </div>
            <div class="dropdown">
                <button class="dropbtn">Size</button>
                <div class="dropdown-content">
                  <a href="#">XS</a> <!-- href generates AI image with size -->
                  <a href="#">S</a> <!-- href generates AI image with size -->
                  <a href="#">M</a> <!-- href generates AI image with size -->
                  <a href="#">L</a> <!-- href generates AI image with size -->
                  <a href="#">XL</a> <!-- href generates AI image with size -->
            </div>
            </div>
              <input type="submit" value="Preview outfit" style="margin-bottom: 10px;">
              <br>
              <button type="button" onclick= >Add to cart</button>
            </div>
        </div>
    </div>
</section>

  <!-- Scripts -->
  <script src="../sharedcode/scripts.js"></script>
</body>
</html>