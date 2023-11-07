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
        $stmt = $con->prepare("SELECT id FROM the_booth.Admin where email = ?");
    
        // bind parameters
        $stmt->bind_param('s', $email);
    
        // Execute statement
        $stmt->execute();
    
        // get result
        $result = $stmt->get_result();
    
        // if username doesn't exist, kill program
        if (mysqli_num_rows($result) < 1) {
            return_json_failure("An account does not exist with this email address.");
        }
    
        // check if password is correct
        // Prepare statement
        $stmt = $con->prepare("SELECT id, email, first_name, last_name, created FROM the_booth.Admin where email = ? and password = SHA2(?, 256)");
    
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
        
        $row = mysqli_fetch_array($result);
        $id = $row['id'];
        $email = $row['email'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $created = $row['created'];

        // set cookie variable with user account info

        // LOGIN TO ACCOUNT ////////////////////////////////////////////////////////////////////
        $account_info = array(
            "id" => $id,
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "created" => $created
        );

        $account_info = json_encode($account_info);
        
        // set cookie variable with user account info
        setcookie("admin_account_info", $account_info, time() + 3600, '/');

        $success_html = <<<HTML
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3>Hello, $first_name! Logging in as admin...</h3>
                    </div>
                </div>
            </div>
        HTML;

        return_json_success($success_html);
    }
    catch (Exception $e) {
        // This will catch PHP exceptions and return as JSON
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>