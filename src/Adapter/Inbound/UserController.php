<?php
namespace App\Adapter\Inbound;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Application\Port\Inbound\UserServicePort;

class UserController {
     private UserServicePort $service;

    public function __construct(UserServicePort $service) {
        $this->service = $service;
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $data = json_decode($request->getBody()->getContents(), true);
        $name = $data['name'] ?? 'Peter';

        $result = $this->service->createUser($name);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
