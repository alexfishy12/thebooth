<?php 
    // This converts errors and warnings to exceptions
    set_error_handler(function ($severity, $message, $file, $line) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    function return_json_success($data) {
        $response = array(
            "status" => "success",
            "data" => $data
        );
        echo json_encode($response);
        die();
    }

    function return_json_failure($data) {
        $response = array(
            "status" => "failure",
            "data" => $data
        );
        echo json_encode($response);
        die();
    }

    function return_json_error($data) {
        $response = array(
            "status" => "error",
            "data" => "PHP ERROR: " . $data
        );
        echo json_encode($response);
        die();
    }
?>