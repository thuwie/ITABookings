<?php
// src/Routes/users.php


use App\Adapter\Inbound\UserController;
use Slim\App;
use App\Application\Service\UserService;
use App\Application\Port\Inbound\UserServicePort;
use App\Adapter\Outbound\UserRepository;

return function (App $app, $twig) {
    $app->post('/register', function ($request, $response, $args) use ($twig) {

    $rawBody = $request->getBody()->getContents();
    // Decode JSON
    $data = json_decode($rawBody, true);

    try {
        $service = $this->get(UserServicePort::class); 

        
        $result = $service->createUser($data);
        // Trả về JSON khi đăng ký thành công
        $result = [
            'status' => 'success',
            'message' => 'Đăng ký thành công',
            'redirect' => '/login'  // JS sẽ xử lý chuyển trang
        ];

    } catch (\Exception $e) {
        $result = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
    });

};
