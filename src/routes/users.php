<?php
// src/Routes/users.php

use App\Adapter\Inbound\UserController;
use Slim\App;
use App\Application\Service\UserService;
use App\Adapter\Outbound\UserRepository;

return function (App $app) {
    $userRepository = new UserRepository();
    $userService = new UserService($userRepository);
    $userController = new UserController($userService);

    $app->post('/users', [$userController, 'create']);

};
