<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class StringHelper {
    public static function sanitize(string $input): string {
        $sanitized = strip_tags($input);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        $sanitized = trim($sanitized);

        return $sanitized;
    }

    public static function mergeSlashes(string $string): string {
        $string = preg_replace('#/+#', '/', $string) ?: $string;

        $string = str_replace('http:/', 'https://', $string);

        return $string;
    }
}