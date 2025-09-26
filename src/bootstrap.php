<?php
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'mysql_db',   // tên container MySQL
    'database'  => 'booking',      // tên DB bạn đã tạo
    'username'  => 'dev',
    'password'  => '02468',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

return $capsule;