<?php
try {
    // URL of the receiving server's script
    $url = 'http://knet-lambda:8080/php/receive.php';

    $customer_id = "20";
    $target_basename = "tester_resize.jpg";

    // The path to the image you want to send
    $imagePath = "../__uploads/customer_images/$customer_id/$target_basename";


    // Open the file
    $file = new CURLFile($imagePath, mime_content_type($imagePath), basename($imagePath));

    // Set up POST fields
    $postFields = array(
        'file' => $file,
        'customer_id' => $customer_id,
        'customer_image_name' => $target_basename
    );

    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Handle the response
    echo $response;
}
catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>