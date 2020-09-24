<?php

declare(strict_types=1);


namespace Config;


use App\Infrastructure\Mapper\Task\PdoTaskMapper;
use App\Infrastructure\Mapper\Task\TaskMapperInterface;
use App\Infrastructure\Mapper\User\PdoUserMapper;
use App\Infrastructure\Mapper\User\UserMapperInterface;
use PDO;
use Psr\Container\ContainerInterface;

class DataMappers
{
    // Regsiter PDO connection
    // Adds DataMappers to DI container
    static function registerDataMappers(ContainerInterface $container)
    {
        self::addDbConnection($container);
        $container->set(
            'user_mapper',
            static function (ContainerInterface $container): UserMapperInterface {
                return new PdoUserMapper($container->get('db'));
            }
        );
        $container->set(
            'task_mapper',
            static function (ContainerInterface $container): TaskMapperInterface {
                return new PdoTaskMapper($container->get('db'), $container->get('user_mapper'));
            }
        );
    }

    private static function addDbConnection(ContainerInterface $container): void
    {
        $container->set(
            'db',
            function (): PDO {
                $dsn = sprintf('mysql:host=%s;dbname=%s', getEnv('DATABASE_HOST'), getenv('DATABASE_NAME'));
                $pdo = new PDO($dsn, getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                return $pdo;
            }
        );
    }

}
