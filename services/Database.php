<?php
declare(strict_types=1);

namespace App\Services;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function connection(): PDO
    {
        if (!self::$instance) {
            $config = require __DIR__ . '/../config/database.php';
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $config['database']['host'],
                $config['database']['name']
            );
            self::$instance = new PDO(
                $dsn,
                $config['database']['user'],
                $config['database']['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }
        return self::$instance;
    }

    public static function testConnection(array $config): bool
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $config['host'],
                $config['name']
            );
            new PDO($dsn, $config['user'], $config['pass']);
            return true;
        } catch (PDOException $exception) {
            return false;
        }
    }
}
