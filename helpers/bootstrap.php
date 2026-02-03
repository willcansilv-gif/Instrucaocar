<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = $baseDir . $relative . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Helpers\Config;

Config::load(__DIR__ . '/../config/app.php');
Config::load(__DIR__ . '/../config/database.php');
