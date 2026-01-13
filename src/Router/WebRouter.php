<?php
namespace App\Router;
use App\Control\ControllerInterface;


class WebRouter implements RouterInterface{
    private ControllerInterface $controller; //теперь контроллер вместо контейнера. Router больше не создает зависимости сам
    public function __construct(ControllerInterface $controller)
    {
        $this->controller = $controller;
    }
    //получаем данные, приводим их к ассоц массиву
    private function readRequestData(){
        $json = file_get_contents('php://input');
        $data = [];
        if ($json){
            $decoded = json_decode($json, true);
            if (json_last_error() === JSON_ERROR_NONE){
                $data = $decoded ?: [];
            }
        }
        $data = array_merge($data, $_POST, $_GET);
        return $data ?: [];
    }
    private function isValidRequest(){
        return isset($input['action']);
    }
    private function sendError($message):void{
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['status'=>'error','message'=>$message]);
        exit;
    }
    private function executeAction($input){
        $action = $input['action'];
        if ($action === 'hold'){
            if (!isset($input['id']) || !isset($input['price'])){
                $this->sendError('Не обнаружены аргументы id и price');
                return;
            }
            $this->controller->holdAction($input['id'],(int)$input['price']);
            return;
        }
        elseif ($action === 'confirm') {
            if (!isset($input['state'])) {
                $this->sendError('Не обнаружен аргумент state');
                return;
            }
            $this->controller->confirmAction('Hold/' . $input['state']);
        } else {
            $this->sendError("Неизвестное действиее $action");
        }

    }
    public function call(array $args = []) {  // default []
        $input = $this->readRequestData();
        if (empty($input['action'])) {  // вместо global $input
            $this->sendError('Неверный запрос');
            return;
        }
        $this->executeAction($input);
    }

}