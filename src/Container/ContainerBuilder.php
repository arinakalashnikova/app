<?php
namespace App\Container;
use App\config\DatabaseConfig;
use App\Control\Controller;
use App\Control\ControllerInterface;
use App\Repository\DBRepository;
use App\Router\Router;
use App\Router\RouterInterface;
use App\Service\CartService;
use App\View\ConsoleView;

class ContainerBuilder
{
    public static function build(): Container
    {
        $container = new Container();
        //теперь собираем все через контейнер
        //PDO
        $container->set(\PDO::class, function () {
            $db = DatabaseConfig::get();

            return new \PDO(
                $db['dsn'],
                $db['user'],
                $db['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        });
        // View
        $container->set(ConsoleView::class, fn() => new ConsoleView());

        // Repository
        $container->set(DBRepository::class, function (Container $c) {
            return new DBRepository($c->get(\PDO::class));
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

        return $container;
    }
}