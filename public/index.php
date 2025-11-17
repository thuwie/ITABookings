<?php
require __DIR__ . '/../vendor/autoload.php';  // load Composer autoload
$capsule = require __DIR__ . '/../src/bootstrap.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Slim\Factory\AppFactory;

// Khai báo thư mục templates
$loader = new FilesystemLoader(__DIR__ . '/../src/templates');
$twig = new Environment($loader);

//Khai báo container để khởi tạo các service cần thiết cho các routes
$containerFactory = require __DIR__ . '/../src/Container/container.php';
$container = $containerFactory();


// Khởi tạo Slim App
AppFactory::setContainer($container);
$app = AppFactory::create();

//Thêm UTF-8 mã hóa kí tự
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
});

$app->add(new \App\Middleware\TwigUserMiddleware($twig));


// Load routes
require __DIR__ . '/../src/routes/files.php';
(require __DIR__ . '/../src/routes/auth.php')($app, $twig);
(require __DIR__ . '/../src/routes/route.php')($app, $twig);
(require __DIR__ . '/../src/routes/travel.spot.php')($app, $twig);
(require __DIR__ . '/../src/routes/food.court.php')($app, $twig);
(require __DIR__ . '/../src/routes/home.php')($app, $twig);
(require __DIR__ . '/../src/routes/provinces.php')($app, $twig);
(require __DIR__ . '/../src/routes/users.php')($app, $twig);
(require __DIR__ . '/../src/routes/provider.php')($app, $twig);
(require __DIR__ . '/../src/routes/driver.php')($app, $twig);
$app->run();
