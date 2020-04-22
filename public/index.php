<?php
session_start();
if ($_SERVER['PHP_SELF'] != '/index.php' && empty($_SESSION['email'])) {
    header('Location: /');
} elseif ($_SERVER['PHP_SELF'] === '/index.php' && isset($_SESSION['email'])) {
    // todo change redirection
    header('Location: /admin/index');
}

require_once __DIR__ . '/../vendor/autoload.php';

if (getenv('ENV') === false) {
    require_once __DIR__ . '/../config/debug.php';
    require_once __DIR__ . '/../config/db.php';
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/routing.php';
