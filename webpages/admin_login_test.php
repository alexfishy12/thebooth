<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>The Booth - Admin Login</title>
    <link rel="icon" href="../_assets/the_booth_logo.png">
    <link href="../_css/style.css" rel="stylesheet" type="text/css"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <div class="wrapper">
  <br>
    <div id="sign_in_form">
      <!-- Tabs Titles -->
      <h2 class="active">The Booth - Admin Sign In</h2>
      <!-- Icon -->
      <div class="fadeIn first">
        <img src="../_assets/the_booth_logo.png" alt="The Booth Logo" height="70" width="70"/>
      </div>
      <!-- Login Form -->
      <form name="input" id="login" method="POST">
        <input id="login_id" type="text" class="fadeIn second" name="login_id" placeholder="Username" required="required">
        <input id="password" type="password" class="fadeIn third" name="password" placeholder="Password" required="required">
        <input type="submit" class="fadeIn fourth" value="login">
      </form>
      <span class="error" id="error_message"></span><br>
      <a href="main_page.php">Return to Main Page</a>
    </div>
    <div id="success_message"></div>
  </div>
</body>
</html>