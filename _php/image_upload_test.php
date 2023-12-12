<?php 

    include("functions.php");

    // get file uploads
    $file = $_FILES["fileToUpload"];

    $target_dir = "../__uploads/";
    $target_file = $target_dir . basename($file["name"]);

    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        return_json_error("Sorry, there was an error uploading your file.");
    }

?>