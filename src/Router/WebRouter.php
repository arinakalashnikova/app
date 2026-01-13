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
    private function handleGetRouting(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $clientBase = 'http://client.local:3000';

        switch ($uri) {
            case '/':
            case '/index':
                header("Location: $clientBase/index.html");
                exit;
            case '/tasks':
            case '/tasks/list':
                header("Location: $clientBase/tasks.html");
                exit;
            case '/hold':
                header("Location: $clientBase/hold.html");
                exit;
            default:
                http_response_code(404);
                header('Content-Type: text/html');
                echo '<h1>Страница не найдена</h1>';
                exit;
        }
    }
    private function isGetRequest(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    private function sendError($message):void{
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['status'=>'error','message'=>$message]);
        exit;
    }
    private function executeAction($input){
        if ($this->isGetRequest()) {
            $this->handleGetRouting();
            return;
        }

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
            $this->sendError("Неизвестное действие $action");
        }
    }
    public function call(array $args = []) {
        $input = $this->readRequestData();
        $this->executeAction($input);
    }

}