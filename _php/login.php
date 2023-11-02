<?php
    session_start();
    include("functions.php");
    //attempt to connect to database
    define("IN_CODE", 1);
    include("dbconfig.php");
    $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or return_json_error("Cannot connect to DB.");

    if (!isset($_POST['email'])) {
        return_json_error("Form submit error: Did not receive email.");
    }
    if (!isset($_POST['password'])) {
        return_json_error("Form submit error: Did not receive password.");
    }

    // get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check if username exists
    // Prepare statement
    $stmt = $con->prepare("SELECT id FROM the_booth.Account where email = ?");

    // bind parameters
    $stmt->bind_param('s', $email);

    // Execute statement
    $stmt->execute();

    // get result
    $result = $stmt->get_result();

    // if username doesn't exist, kill program
    if (mysqli_num_rows($result) < 1) {
        return_json_error("Email is not linked to any account with us.");
    }

    // check if password is correct
    // Prepare statement
    $stmt = $con->prepare("SELECT id, email, first_name, last_name, type, store_name, created first FROM the_booth.Account where email = ? and password = SHA2(?, 256)");

    // bind parameters
    $stmt->bind_param('ss', $email, $password);

    // Execute statement
    $stmt->execute();

    // get result
    $result = $stmt->get_result();

    // if password is incorrect, kill program
    if (mysqli_num_rows($result) < 1) {
        return_json_error("Incorrect password.");
    }
    
    $account_info = mysqli_fetch_array($result);
    
    // set session variable
    $_SESSION["start"] = time();
    $_SESSION['expire'] = $_SESSION['start'] + (3600);
    $_SESSION["account_info"] = $account_info;

    return_json_success("Login successful.");
?>