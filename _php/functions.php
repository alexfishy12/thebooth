<?php 
    function return_json_success($data) {
        $response = array(
            "status" => "success",
            "data" => $data
        );
        echo json_encode($response);
        die();
    }

    function return_json_error($data) {
        $response = array(
            "status" => "error",
            "data" => $data
        );
        echo json_encode($response);
        die();
    }
?>