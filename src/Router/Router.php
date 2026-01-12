<?php
namespace App\Router;
use App\Container\Container;
use App\Control\Controller;
class Router{
    private array $routes = [];
    private Container $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function add(string $command, string $action){
        $this->routes[$command] = $action;
    }

    public function call(array $args){
        $options = getopt('', ['hold:', 'price:', 'order:', 'confirm:']);

        if (empty($options)) {
            echo "список аргументов пуст\n";
            return;
        }

        $controller = $this->container->get(Controller::class);

        if (isset($options['hold'])) {
            $controller->holdAction($options['hold'], $options['price'] ?? null);
        } elseif (isset($options['confirm'])) {
            $controller->confirmAction('Hold/' . $options['confirm']);
        } else {
            echo "неизвестная команда\n";
        }
    }
}
