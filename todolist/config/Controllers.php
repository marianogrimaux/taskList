<?php

declare(strict_types=1);

namespace Config;

use App\Controller\TaskController;
use App\Controller\UserController;
use Psr\Container\ContainerInterface;

class Controllers
{
    static function RegisterControllers(ContainerInterface $container)
    {
        $container->set(
            'TaskController',
            function (ContainerInterface $c) {
                return new TaskController();
            }
        );
        $container->set(
            'UserController',
            function (ContainerInterface $c) {
                return new UserController();
            }
        );
    }
}
