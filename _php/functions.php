<?php 
    function return_json_success($data) {
        $response = array(
            "success" => true,
            "data" => $data
        );
        echo json_encode($response);
        die();
    }

    function return_json_error($data) {
        $response = array(
            "success" => false,
            "data" => $data
        );
        echo json_encode($response);
        die();
    }
?>