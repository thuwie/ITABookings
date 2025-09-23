<?php
$host = 'mysql_db';      // tên container MySQL
$db   = 'booking';         // database
$user = 'dev';           // user dev
$pass = '02468';       // password dev
$port = 3306;            // port trong container

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "✅ Connected to MySQL!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
