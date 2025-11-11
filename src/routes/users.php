<?php
// src/Routes/users.php


use App\Adapter\Inbound\UserController;
use Slim\App;
use App\Application\Service\UserService;
use App\Application\Port\Inbound\UserServicePort;
use App\Adapter\Outbound\UserRepository;

return function (App $app, $twig) {
    $app->post('/register', function ($request, $response, $args) use ($twig) {

       $service = $this->get(UserServicePort::class); 

       $user = $request->getParsedBody();        
       
        $result = $service->createUser($user);

        // Trả về JSON đúng với dữ liệu service trả
    
         $response->getBody()->write(json_encode($result)); 
         
        // Đặt header Content-Type cho chuẩn REST
        return $response->withHeader('Content-Type', 'application/json');
    });
};
