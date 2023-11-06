<?php
    // IN_CODE is a constant that makes sure dbconfig.php is accessed securely
    define("IN_CODE", 1);
    try {
        include("dbconfig.php");
        include("functions.php");

        //attempt to connect to database
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
        $stmt = $con->prepare("SELECT id FROM store_template.Customer where email = ?");
    
        // bind parameters
        $stmt->bind_param('s', $email);
    
        // Execute statement
        $stmt->execute();
    
        // get result
        $result = $stmt->get_result();
    
        // if username doesn't exist, kill program
        if (mysqli_num_rows($result) < 1) {
            return_json_failure("Email is not linked to any account with us.");
        }
    
        // check if password is correct
        // Prepare statement
        $stmt = $con->prepare("SELECT id, first_name, last_name, email, address, city, state, zip, created FROM store_template.Customer where email = ? and password = SHA2(?, 256)");
    
        // bind parameters
        $stmt->bind_param('ss', $email, $password);
    
        // Execute statement
        $stmt->execute();
    
        // get result
        $result = $stmt->get_result();
    
        // if password is incorrect, kill program
        if (mysqli_num_rows($result) < 1) {
            return_json_failure("Incorrect password.");
        }
        
        $account_info = mysqli_fetch_array($result);
        $id = $account_info['id'];
        $first_name = $account_info['first_name'];
        $last_name = $account_info['last_name'];
        $email = $account_info['email'];
        $address = $account_info['address'];
        $city = $account_info['city'];
        $state = $account_info['state'];
        $zip = $account_info['zip'];
        $created = $account_info['created'];

        $account_info = array(
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "address" => $address,
            "city" => $city,
            "state" => $state,
            "zip" => $zip,
            "created" => $created
        );

        $account_info = json_encode($account_info);
        
        // set cookie variable with user account info
        setcookie("customer_account_info", $account_info, time() + 3600);
        return_json_success("Login successful.");
    }
    catch (Exception $e) {
        // This will catch PHP exceptions and return as JSON
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>