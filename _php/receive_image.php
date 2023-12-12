<?php 

    $customer_id = $_POST['customer_id'];
    $customer_image_name = $_POST['customer_image_name'];

    $target_dir = "./temp_file_storage/";
    $target_file = $target_dir . basename($customer_image_name);

    $src_image = "https://obi2.kean.edu/~fisheral@kean.edu/thebooth/__uploads/customer_images/" . $customer_id . "/" . $customer_image_name;
    copy($src_image, $target_file)

    // run preprocessing


?>