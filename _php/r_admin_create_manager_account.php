<?php 
    // attempt to connect to DB
    define("IN_CODE", 1);
    try {
        include("dbconfig.php");
        include("functions.php");
        $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

        // CHECK THAT ALL FORM VARIABLES ARE SET //////////////////////////////////////////////////////

        /* 
            create table Employee (
                id int primary key not null auto_increment,
                email varchar(255) UNIQUE not null,
                first_name varchar(50) not null,
                last_name varchar(50) not null,
                password varchar(100) not null,
                type char(1),
                created datetime not null
            );
        */


        $variable_not_set = false;
        $error_string = "";
        if (!isset($_POST['first_name'])) {
            $error_string = $error_string . "Form submit error: First name not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['last_name'])) {
            $error_string = $error_string . "Form submit error: Last name not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['email'])) {
            $error_string = $error_string . "Form submit error: Email not received.\n";
            $variable_not_set = true;
        }
        if (!isset($_POST['password'])) {
            $error_string = $error_string . "Form submit error: Password not received.\n";
            $variable_not_set = true;
        }

        // if any of the variables weren't set, kill program
        if ($variable_not_set) {
            return_json_error($error_string);
        }

        // get variables from form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // check that all variables are valid
        $variable_not_valid = false;
        $error_string = "";

        if (strlen($first_name) > 255) {
            $error_string = $error_string . "Insert failed: First name must be no more than 255 characters.<br>";
            $variable_not_valid = true;
        }
        if (strlen($last_name) > 255) {
            $error_string = $error_string . "Insert failed: Last name must be no more than 255 characters.<br>";
            $variable_not_valid = true;
        }
        if (strlen($email) > 255) {
            $error_string = $error_string . "Insert failed: Email address must be no more than 255 characters.<br>";
            $variable_not_valid = true;
        }
        if (strlen($password) > 255) {
            $error_string = $error_string . "Insert failed: Password must be no more than 255 characters.<br>";
            $variable_not_valid = true;
        }
        if ($variable_not_valid) {
            return_json_failure($error_string);
        }

        // CHECK FOR DUPLICATES
        $query = "SELECT email FROM store_template.Employee WHERE email = ?;";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) > 0) {
            return_json_failure("Insert failed: Email is already linked to an account with us.<br>");
        }

        // INSERT ACCOUNT INTO DATABASE //////////////////////////////////////////////////////

        // Prepare statement
        $query = "INSERT INTO store_template.Employee (first_name, last_name, email, password, type, created) VALUES (?, ?, ?, SHA2(?, 256), 'M', NOW());";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ssss', $first_name, $last_name, $email, $password);
        
        if (!$stmt) {
            return_json_error("Insert failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
        }
        
        if (!$stmt->execute()) {
            return_json_error("Insert failed (Execute failed): (" . $stmt->errno . ") " . $stmt->error);
        }
        
        if ($stmt->affected_rows == 0) {
            return_json_error("Insert failed, 0 affected rows.");
        }

        // SUCCESS //////////////////////////////////////////////////////

        $success_html = <<<HTML
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3>Manager Account for $first_name $last_name has been created!</h3>
                        <br>
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
        
        return_json_success($success_html);
        
        $stmt->close();
    }
    catch (Exception $e) {
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>