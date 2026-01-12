<?php
require __DIR__ . '/vendor/autoload.php';

use App\Container\ContainerBuilder;
use App\Router\RouterInterface;

const SUCCESS = "Успех";
const UNFOUND = "Не найдено";
const ROOT_DIR = __DIR__;


//настраиваем router
$container = ContainerBuilder::build();
$router = $container->get(RouterInterface::class);
//запускаем приложение при помощи router
$router->call($argv);


