<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class LogHelper {
    public static function d(mixed $data): void {
        if (gettype($data) != 'string') {
            $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        echo $data;
    }

    public static function dd(mixed $data): void {
        self::d($data);
        exit;
    }
}