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

        $firstName   = $data['firstName'] ?? '';
        $lastName    = $data['lastName'] ?? '';
        $password    = $data['password'] ?? '';
        $email       = $data['email'] ?? '';
        $phoneNumber = $data['phoneNumber'] ?? null;
        $portrait    = $data['portrait'] ?? null;

        try {
            $result = $this->service->createUser(
                $firstName,
                $lastName,
                $password,
                $email,
                $phoneNumber,
                $portrait
            );

            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(201); // Created
        } catch (\DomainException $e) {
            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400); // Bad Request
        }
    }
}
