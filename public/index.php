<?php
require __DIR__ . '/../vendor/autoload.php';  // load Composer autoload

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Slim\Factory\AppFactory;

// Khai báo thư mục templates
$loader = new FilesystemLoader(__DIR__ . '/../src/templates');
$twig = new Environment($loader);

// Khởi tạo Slim App
$app = AppFactory::create();

// Load routes
(require __DIR__ . '/../src/routes/home.php')($app, $twig);
(require __DIR__ . '/../src/routes/users.php')($app);

$app->run();
