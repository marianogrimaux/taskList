<?php

declare(strict_types=1);

namespace Config;

use App\Repository\TaskRepository;
use App\Repository\TaskRepositoryInterface;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;
use Psr\Container\ContainerInterface;

class Repositories
{
    // Adds repositories to DI container.
    public static function registerRepositories(ContainerInterface $container): void
    {
        $container->set(
            'user_repository',
            static function (ContainerInterface $container): UserRepositoryInterface {
                return new UserRepository($container->get('user_mapper'));
            }
        );
        $container->set(
            'task_repository',
            static function (ContainerInterface $container): TaskRepositoryInterface {
                return new TaskRepository($container->get('task_mapper'));
            }
        );
    }
}
