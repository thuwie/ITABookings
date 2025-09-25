<?php
// src/Routes/users.php

use App\Adapter\Inbound\UserController;
use Slim\App;
use App\Application\Service\UserService;

return function (App $app) {
    $userService = new UserService();
    $userController = new UserController($userService);

    $app->post('/users', [$userController, 'create']);

};
