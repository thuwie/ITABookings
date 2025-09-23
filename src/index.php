<?php
require __DIR__ . '/vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Khai báo thư mục templates
$loader = new FilesystemLoader(__DIR__ . '/templates');

// Khởi tạo Twig
$twig = new Environment($loader);

// Render file home.twig
echo $twig->render('pages/home.html.twig', ['name' => 'Peter']);
