<?php
session_start();
if (stristr($_SERVER['REQUEST_URI'], 'admin') != false && (bool)$_SESSION['is_admin'] === false) {
    header('Location: /');
    exit;
}
$allowedUrl = ['/', '/user/register', '/user/insertuser'];
if (empty($_SESSION['email']) && !in_array($_SERVER['REQUEST_URI'], $allowedUrl)) {
    header('Location: /');
    exit;
}

$test  = stristr($_SERVER['REQUEST_URI'], '/joshua/index');
$test2 = stristr($_SERVER['REQUEST_URI'], '/user/register');
$test3 = $_SERVER['REQUEST_URI'] === '/';
$test4 = isset($_SESSION['email']);
if (($test || $test2 || $test3) && $test4) {
    header('Location: /joshua/home');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

if (getenv('ENV') === false) {
    require_once __DIR__ . '/../config/debug.php';
    require_once __DIR__ . '/../config/db.php';
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/routing.php';
