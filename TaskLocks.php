<?php
require __DIR__ . '/vendor/autoload.php';

use App\Control\Controller;
use App\Container\Container;
use App\View\ConsoleView;
use App\Repository\DBRepository;
use App\Service\CartService;
use PDO;

const SUCCESS = "Успех";
const UNFOUND = "Не найдено";

$container = new Container();
//создание PDO
$container->set(PDO::class, function () {
    $env = parse_ini_file(__DIR__ . '/.env');
    if ($env === false) {
        throw new RuntimeException('.env not found');
    }

    $dsn = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        $env['DB_HOST'],
        $env['DB_PORT'],
        $env['DB_NAME']
    );

    return new PDO(
        $dsn,
        $env['DB_USER'],
        $env['DB_PASSWORD'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
});
//
//собираем все
// $view = new ConsoleView();
// $repository = new DBRepository($pdo);
// $service = new CartService($repository);
// $controller = new Controller($view, $service);

// View
$container->set(ConsoleView::class, fn() => new ConsoleView());

// Repository
$container->set(DBRepository::class, function (Container $c) {
    return new DBRepository($c->get(PDO::class));
});

// Service
$container->set(CartService::class, function (Container $c) {
    return new CartService($c->get(DBRepository::class));
});

// Controller
$container->set(Controller::class, function (Container $c) {
    return new Controller(
        $c->get(ConsoleView::class),
        $c->get(CartService::class)
    );
});

//достаем контроллер
$controller = $container->get(Controller::class);
//запускаем при помощи контроллера
$options = getopt('', ['hold:', 'price:', 'order:', 'confirm:']);
if (isset($options['hold'])) {
    $controller->holdAction($options['hold'], $options['price']);
}

if (isset($options['confirm'])) {
    $controller->confirmAction('Hold/' . $options['confirm']);
}

