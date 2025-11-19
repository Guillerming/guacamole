<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class StringHelper {
    public static function uuid(): string {
        $data = random_bytes(16);

        // Set version to 0100 (UUID v4)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10 (variant bits)
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return sprintf('%08s-%04s-%04s-%04s-%12s',
            bin2hex(substr($data, 0, 4)),
            bin2hex(substr($data, 4, 2)),
            bin2hex(substr($data, 6, 2)),
            bin2hex(substr($data, 8, 2)),
            bin2hex(substr($data, 10, 6))
        );
    }

    public static function sanitize(string $input): string {
        $sanitized = strip_tags($input);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        $sanitized = trim($sanitized);

        return $sanitized;
    }

    public static function mergeSlashes(string $string): string {
        $string = preg_replace('#/+#', '/', $string) ?: $string;

        $string = str_replace('http:/', 'http://', $string);
        $string = str_replace('https:/', 'https://', $string);

        return $string;
    }
}