<?php

declare(strict_types=1);


namespace App\Controller;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class TaskController
{

    public function createTask(Request $request, Response $response, $args) {
        $response->getBody()->write('create a task');
        return $response;
    }

    public function editTask(Request $request, Response $response, $args) {
        $response->getBody()->write('edit a task');
        return $response;

    }

    public function deleteTask(Request $request, Response $response, $args) {
        $response->getBody()->write('delete a task');
        return $response;

    }

    public function getTask(Request $request, Response $response, $args) {
        $response->getBody()->write('get a task');
        return $response;
    }

    public function dailyTasks(Request $request, Response $response, $args) {
        $response->getBody()->write('get daily tasks');
        return $response;
    }
}
