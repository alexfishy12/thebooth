<?php 
    // attempt to connect to DB
    define("IN_CODE", 1);
    try {
        include("dbconfig.php");
        include("functions.php");
        $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

        //Check For ID
        if (!isset($_POST['id'])) {
            return_json_error("Form submit error: ID not received.");
        }

        // get variables from form
        $id = $_POST['id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        
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
        if ($variable_not_valid) {
            return_json_failure($error_string);
        }

        // CHECK FOR DUPLICATES
        $query = "SELECT email FROM store_template.Employee WHERE email = ? AND id <> ?;";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) > 0) {
            return_json_failure("Update failed: Email is already linked to an account with us.<br>");
        }

        // UPDATE ACCOUNT IN DATABASE //////////////////////////////////////////////////////

        // Prepare statement
        $query = "UPDATE store_template.Employee SET first_name = ?, last_name = ?, email = ? WHERE id = ?;";
        $stmt = $con->prepare($query);
        $stmt->bind_param('sssi', $first_name, $last_name, $email, $id);
        
        if (!$stmt) {
            return_json_error("Update failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
        }
        
        if (!$stmt->execute()) {
            return_json_error("Update failed (Execute failed): (" . $stmt->errno . ") " . $stmt->error);
        }
        
        if ($stmt->affected_rows === 0) {
            return_json_error("Update failed, 0 affected rows. It's possible that the new data is the same as the existing data.");
        }

        // SUCCESS //////////////////////////////////////////////////////

        return_json_success("Manager account updated successfully.");
        
        $stmt->close();
    }
    catch (Exception $e) {
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>