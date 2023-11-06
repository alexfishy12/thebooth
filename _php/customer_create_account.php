<?php 
    // attempt to connect to DB
    define("IN_CODE", 1);
    include("dbconfig.php");
    include("functions.php");
    $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die ("<br>Cannot connect to DB.\n");

    // CHECK THAT ALL FORM VARIABLES ARE SET //////////////////////////////////////////////////////

    /* 
        create table Customer (
            id int primary key not null auto_increment,
            first_name varchar(255) not null,
            last_name varchar(255) not null,
            email varchar(255) UNIQUE not null,
            password varchar(255) not null,
            address varchar(255) not null,
            city varchar(255) not null,
            state varchar(255) not null,
            zip varchar(255) not null,
            created datetime not null
        );

        create table Customer_Image (
            id int primary key not null auto_increment,
            customer_id int not null,
            image_og blob not null,
            image_pp blob not null,
            foreign key (customer_id) references Customer(id)
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
    if (!isset($_POST['address'])) {
        $error_string = $error_string . "Form submit error: Address not received.\n";
        $variable_not_set = true;
    }
    if (!isset($_POST['city'])) {
        $error_string = $error_string . "Form submit error: City not received.\n";
        $variable_not_set = true;
    }
    if (!isset($_POST['state'])) {
        $error_string = $error_string . "Form submit error: State not received.\n";
        $variable_not_set = true;
    }
    if (!isset($_POST['zip'])) {
        $error_string = $error_string . "Form submit error: Zipcode not received.\n";
        $variable_not_set = true;
    }
    if (!isset($_POST['image'])) {
        $error_string = $error_string . "Form submit error: Image not received.\n";
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
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $image = $_POST['image'];

    
    // check that all variables are valid
    $variable_not_valid = false;
    $error_string = "";

    if (strlen($first_name) > 15) {
        $error_string = $error_string . "Insert failed: First name must be no more than 255 characters.<br>";
        $variable_not_valid = true;
    }
    if (strlen($last_name) > 15) {
        $error_string = $error_string . "Insert failed: Last name must be no more than 255 characters.<br>";
        $variable_not_valid = true;
    }
    if (strlen($email) > 15) {
        $error_string = $error_string . "Insert failed: Email address must be no more than 255 characters.<br>";
        $variable_not_valid = true;
    }
    if (strlen($password) > 15) {
        $error_string = $error_string . "Insert failed: Password must be no more than 255 characters.<br>";
        $variable_not_valid = true;
    }
    if (strlen($address) > 200) {
        $error_string = $error_string . "Insert failed: Address must be no more than 200 characters.<br>";
        $variable_not_valid = true;
    }
    if (strlen($city) > 15) {
        $error_string = $error_string . "Insert failed: City must be no more than 15 characters.<br>";
        $variable_not_valid = true;
    }
    if (strlen($state) > 2) {
        $error_string = $error_string . "Insert failed: State must be no more than 2 characters.<br>";
        $variable_not_valid = true;
    }
    if (strlen($zip) > 10) {
        $error_string = $error_string . "Insert failed: Zipcode must be no more than 10 characters.<br>";
        $variable_not_valid = true;
    }
    if ($variable_not_valid) {
        return_json_failure($error_string);
    }

    // CHECK FOR DUPLICATES
    $query = "SELECT email FROM store_template.Customer WHERE email = ?;";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if (mysqli_num_rows($result) > 0) {
        return_json_failure("Insert failed: Email is already linked to an account with us.<br>");
    }

    // INSERT ACCOUNT INTO DATABASE //////////////////////////////////////////////////////

    // Prepare statement
    $query = "INSERT INTO store_template.Customer (first_name, last_name, email, password, address, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = $con->prepare($query);
    $stmt->bind_param('sssssssss', $first_name, $last_name, $email, $password, $address, $city, $state, $zip);
    
    if (!$stmt) {
        return_json_error("Insert failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
    }
    
    if (!$stmt->execute()) {
        return_json_error("Insert failed (Execute failed): (" . $stmt->errno . ") " . $stmt->error);
    }
    
    if ($stmt->affected_rows == 0) {
        return_json_error("Insert failed, 0 affected rows.");
    }

    $success_html = <<<HTML
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1>Welcome to The Booth, $first_name!.</h1>
                    Redirecting to main page...
                </div>
            </div>
        </div>
    HTML;
    
    return_json_success($success_html);
    
    $stmt->close();
?>