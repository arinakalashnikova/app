<?php
namespace App\View;
class WebView implements ViewInterface{
    public function success($message){
        header('Content-type: application/json');
        $response = [
            'status' => 'success',
            'message' => $message
        ];
        echo json_encode($response);
        exit;
    }

    public function error($message){
        http_response_code(400);
        header('Content-type: application/json');
        $response = [
            'status'=>'error',
            'message' => $message
        ];
        echo json_encode($response);
        exit;
    }
}