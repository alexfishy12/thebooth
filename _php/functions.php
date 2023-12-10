<?php 
    // This converts errors and warnings to exceptions
    set_error_handler(function ($severity, $message, $file, $line) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    /**
     * This function is meant to respond to the client, including the success status and the data to send.
     * 
     * @return json The connection to the database.
     */
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

    
    function internal_return_json_success($data) {
        $response = array(
            "status" => "success",
            "data" => $data
        );
        return json_encode($response);
    }
    
    function internal_return_json_failure($data) {
        $response = array(
            "status" => "failure",
            "data" => $data
        );
        return json_encode($response);
    }

    function internal_return_json_error($data) {
        $response = array(
            "status" => "error",
            "data" => "INTERNAL PHP ERROR: " . $data
        );
        return json_encode($response);
    }
?>