<?php
declare(strict_types=1);

namespace App\Helpers;

class Config
{
    private static array $items = [];

    public static function load(string $file): void
    {
        if (!file_exists($file)) {
            return;
        }
        $data = require $file;
        if (is_array($data)) {
            self::$items = array_replace_recursive(self::$items, $data);
        }
    }

    public static function get(string $key, $default = null)
    {
        $segments = explode('.', $key);
        $value = self::$items;
        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }
}
