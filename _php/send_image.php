<?php 

    include("functions.php");
    $customer_id = "15";
    $target_basename = "mister_tester.png";

     // The data you want to send via POST
     $data = [
        'customer_id' => $customer_id,
        'customer_image_name' => $target_basename, 
    ];

    // URL to send the POST request to
    $url = 'http://knet-lambda:8080/php/receive.php';

    // Create a stream context
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);

    // Send the POST request
    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        return_json_error("Sorry, there was an error uploading your file.");
    }

    // Decode the response
    echo $response;
    die();
    $responseData = json_decode($response, true);
    if ($responseData['status'] == 'success') {
        return_json_success($responseData['data']);
    } else {
        return_json_error($responseData['data']);
    }
?>