<?php
require __DIR__ . '/../vendor/autoload.php';  // load Composer autoload
$capsule = require __DIR__ . '/../src/bootstrap.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Slim\Factory\AppFactory;

// Khai báo thư mục templates
$loader = new FilesystemLoader(__DIR__ . '/../src/templates');
$twig = new Environment($loader);


// Tạo repository và service sau khi bootstrap
$userRepository = new \App\Adapter\Outbound\UserRepository();
$userService = new \App\Application\Service\UserService($userRepository);
$userController = new \App\Adapter\Inbound\UserController($userService);


// Khởi tạo Slim App
$app = AppFactory::create();

// Load routes
(require __DIR__ . '/../src/routes/home.php')($app, $twig);
(require __DIR__ . '/../src/routes/locations.php')($app, $twig);
(require __DIR__ . '/../src/routes/users.php')($app);

$app->run();
