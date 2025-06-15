<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class UrlHelper {
    public static function getBaseUrl(): string {
        $protocol = 'https';
        $host = DataRequesterHelper::getServerData('HTTP_HOST') ?? 'localhost';

        return rtrim("{$protocol}{$host}", '/');
    }

    public static function getPath(): string {
        $path = DataRequesterHelper::getServerData('REQUEST_URI') ?? '';
        $queryPos = strpos($path, '?');
        if ($queryPos !== false) {
            $path = substr($path, 0, $queryPos);
        }

        $path = rtrim($path, '/');
        if (!strlen($path)) {
            return '/';
        }

        return $path;
    }

    public static function getQuery(): string {
        return DataRequesterHelper::getServerData('QUERY_STRING') ?? '';
    }

    public static function getQueryParam(string $name): ?string {
        $queryString = self::getQuery();
        parse_str($queryString, $params);
        $value = $params[$name] ?? null;
        if (is_array($value)) {
            return null;
        }

        return $value;
    }
}