<?php

declare(strict_types=1);

namespace Config;

use Slim\App;

class Routes
{
    public static function registerRoutes(App $app): void
    {
        /*
         * Fixme:
         * routes groups are not working for some reason
         * routes added one by one to avoid troubleshooting time.
         * Route not found exception when using groups
         */
        $app->post('/user/create', 'UserController:createUser');
        $app->get('/user/{userId:[0-9]+}', 'UserController:getUser');
        $app->put('/user/{userId:[0-9]+}', 'UserController:editUser');
        $app->post('/tasks/', 'TaskController:createTask');
        $app->get('/tasks/', 'TaskController:dailyTasks');
        $app->get('/tasks/{userId:[0-9]+}', 'TaskController:getTask');
        $app->put('/tasks/{userId:[0-9]+}', 'TaskController:editTask');
        $app->delete('/tasks/{userId:[0-9]+}', 'TaskController:deleteTask');
    }
}
