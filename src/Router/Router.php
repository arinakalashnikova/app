<?php
namespace App\Router;
use App\Control\ControllerInterface;

class Router implements RouterInterface{
    private ControllerInterface $controller; //теперь контроллер вместо контейнера. Router больше не создает зависимости сам
    public function __construct(ControllerInterface $controller)
    {
        $this->controller = $controller;
    }

    public function call(array $args){
        $options = getopt('', ['hold:', 'price:', 'order:', 'confirm:']);

        if (empty($options)) {
            echo "список аргументов пуст\n";
            return;
        }

        if (isset($options['hold'])) {
            $this->controller->holdAction($options['hold'], $options['price'] ?? null);
        } elseif (isset($options['confirm'])) {
            $this->controller->confirmAction('Hold/' . $options['confirm']);
        } else {
            echo "неизвестная команда\n";
        }
    }
}
