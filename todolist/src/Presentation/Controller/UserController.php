<?php

declare(strict_types=1);


namespace App\Presentation\Controller;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UserController
{
    public function getUser(Request $request, Response $response, $args)
    {
        $response->getBody()->write('get user');
        return $response;
    }

    public function editUser(Request $request, Response $response, $args)
    {
        $response->getBody()->write('edit user');
        return $response;
    }

    public function createUser(Request $request, Response $response, $args)
    {
        $response->getBody()->write('create user');
        return $response;
    }
}
