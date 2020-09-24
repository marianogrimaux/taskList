<?php

declare(strict_types=1);

namespace Config;

use Slim\App;
use Slim\Factory\AppFactory;

class Bootstrap
{

    /*
     * Create Slim App, used to declare dependencies and
     * inject services, repositories & routes into the appContainer
     */
    final static function buildApp(): App
    {
        $container = new \DI\Container();
        // Adds Conection and dataMappers
        DataMappers::registerDataMappers($container);
        // Adds repositories
        Repositories::registerRepositories($container);
        // Adds controllers
        Controllers::RegisterControllers($container);
        // Set loaded DI Container to appFactory
        AppFactory::setContainer($container);
        //Creates and return the app
        $app = AppFactory::create();
        Routes::registerRoutes($app);
        return $app;
    }
}
