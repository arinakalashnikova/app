<?php
require_once __DIR__ . '/Control/Controller.php';
const SUCCESS = "Успех";
const UNFOUND = "Не найдено";
//создание PDO
$env = parse_ini_file(__DIR__ . '/.env');
$_ENV = array_merge($_ENV, $env);
$dsn = sprintf(
    'pgsql:host=%s;port=%s;dbname=%s',
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_NAME']
);
$pdo = new PDO(
    $dsn,
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);
//
//собираем все
$view = new ConsoleView();
$repository = new DBRepository($pdo);
$service = new CartService($repository);
$controller = new Controller($view, $service);

//запускаем при помощи контроллера
$options = getopt('', ['hold:', 'price:', 'order:', 'confirm:']);
if (isset($options['hold'])) {
    $controller->holdAction($options['hold'], $options['price']);
}

if (isset($options['confirm'])) {
    $controller->confirmAction('Hold/' . $options['confirm']);
}

