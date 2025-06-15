<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class DataRequesterHelper {
    private static function processData(mixed $data): ?string {
        if (is_scalar($data)) {
            return StringHelper::sanitize((string) $data);
        }

        return null;
    }

    public static function getServerData(string $name): ?string {
        if (!isset($_SERVER[$name])) {
            return null;
        }

        return self::processData($_SERVER[$name]);
    }

    public static function getRequestData(string $name): ?string {
        if (!isset($_REQUEST[$name])) {
            return null;
        }

        return self::processData($_REQUEST[$name]);
    }

    public static function getPostData(string $name): ?string {
        if (!isset($_POST[$name])) {
            return null;
        }

        return self::processData($_POST[$name]);
    }

    public static function getGetData(string $name): ?string {
        if (!isset($_GET[$name])) {
            return null;
        }

        return self::processData($_GET[$name]);
    }
}