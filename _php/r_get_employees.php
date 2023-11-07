<?php
    define("IN_CODE", 1);
    include("dbconfig.php");
    include("functions.php");
    try {
        $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or return_json_error("<br>Cannot connect to DB.\n");

        $query = "SELECT id, first_name, last_name, email, type, created FROM store_template.Employee;";

        $result = mysqli_query($con, $query);
        
        if (mysqli_num_rows($result) < 1) {
            return_json_failure("There are zero employees in the database.");
            die();
        }

        $success_html = "<table class='table table-hover'><tr><th>ID<th>First Name<th>Last Name<th>Email<th>Type<th>Created<th>";
        while($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $type = $row['type'];
            $created = $row['created'];
            $success_html = $success_html . "<tr><td>$id<td>$first_name<td>$last_name<td>$email<td>$type<td>$created";
            $success_html = $success_html . "<td><a class='btn' href='admin_edit_employee.php?id=$id&first_name=$first_name&last_name=$last_name&email=$email&type=$type&created=$created'>Edit</a>";
        }
        $success_html = $success_html . "</table>";

        return_json_success($success_html);

        mysqli_free_result($result);
    }
    catch (Exception $e) {
        // This will catch PHP exceptions and return as JSON
        return_json_error('Caught exception: ' . $e->getMessage());
    }
?>