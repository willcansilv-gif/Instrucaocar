<?php
declare(strict_types=1);

session_start();

$installedLock = __DIR__ . '/storage/installed.lock';
if (!file_exists($installedLock)) {
    header('Location: /install/index.php');
    exit;
}

require __DIR__ . '/config/app.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/helpers/bootstrap.php';

use App\Helpers\Router;

$router = new Router();
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/dashboard', 'DashboardController@index');
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
