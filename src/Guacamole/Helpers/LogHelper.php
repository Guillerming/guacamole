<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class LogHelper {
    public static function d(mixed $data): void {
        if (gettype($data) != 'string') {
            $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        echo '<pre>'.$data.'</pre>';
    }

    public static function dd(mixed $data): void {
        self::d($data);
        exit;
    }

    public static function vd(mixed $data): void {
        var_dump($data);
    }

    public static function vdd(mixed $data): void {
        self::vd($data);
        exit;
    }
}