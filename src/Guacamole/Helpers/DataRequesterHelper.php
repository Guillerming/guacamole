<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class DataRequesterHelper {
    public static function processData(mixed $data): ?string {
        if (is_scalar($data)) {
            return StringHelper::sanitize((string) $data);
        }

        return null;
    }

    /**
     * Get a value from $_SERVER, sanitized.
     * @param string $name
     * @return string|null
     */
    public static function getServerData(string $name): ?string {
        if (!isset($_SERVER[$name])) {
            return null;
        }

        return self::processData($_SERVER[$name]);
    }

    /**
     * Get a value from $_REQUEST, sanitized.
     * @param string $name
     * @return string|null
     */
    public static function getRequestData(string $name): ?string {
        if (!isset($_REQUEST[$name])) {
            return null;
        }

        return self::processData($_REQUEST[$name]);
    }

    /**
     * Get a value from $_POST, sanitized.
     * @param string $name
     * @return string|null
     */
    public static function getPostData(string $name): ?string {
        if (!isset($_POST[$name])) {
            return null;
        }

        return self::processData($_POST[$name]);
    }

    /**
     * Get a value from $_GET, sanitized.
     * @param string $name
     * @return string|null
     */
    public static function getGetData(string $name): ?string {
        if (!isset($_GET[$name])) {
            return null;
        }

        return self::processData($_GET[$name]);
    }

    /**
     * Get a value from $_COOKIE, sanitized.
     * @param string $name
     * @return string|null
     */
    public static function getCookieData(string $name): ?string {
        if (!isset($_COOKIE[$name])) {
            return null;
        }

        return self::processData($_COOKIE[$name]);
    }

    /**
     * Get a value from $_FILES, sanitized (returns the file array or null).
     * @param string $name
     * @return array|null
     */
    public static function getFileData(string $name): ?array {
        if (!isset($_FILES[$name])) {
            return null;
        }

        // No sanitization for files, just return the array
        return $_FILES[$name];
    }
}