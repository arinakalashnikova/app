<?php
require __DIR__ . '/vendor/autoload.php';

use App\Control\Controller;
use App\Container\Container;
use App\View\ConsoleView;
use App\Repository\DBRepository;
use App\Service\CartService;
use App\Router\Router;
use App\Router\RouterInterface;
use App\Control\ControllerInterface;

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
// $view = new ConsoleView();
// $repository = new DBRepository($pdo);
// $service = new CartService($repository);
// $controller = new Controller($view, $service);

//теперь собираем все через контейнер
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
$container->set(ControllerInterface::class, fn($c) =>
new Controller($c->get(ConsoleView::class), $c->get(CartService::class))
);

// Router
$container->set(RouterInterface::class, fn($c) =>
new Router($c->get(ControllerInterface::class))  //теперь передаем готовый контроллер вместо контейнера
);

//настраиваем router
$router = $container->get(RouterInterface::class);
//запускаем приложение при помощи router
$router->call($argv);


