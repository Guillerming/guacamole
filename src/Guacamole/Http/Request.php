<?php

declare(strict_types=1);

namespace Guacamole\Http;

use Guacamole\Helpers\DataRequesterHelper;
use Guacamole\Helpers\UrlHelper;
use Guacamole\Http\Enums\HttpMethods;

class Request {
    /**
     * Get the HTTP method of the current request (GET, POST, etc).
     * 
     */
    public static function getHttpMethod(): HttpMethods {
        $method = DataRequesterHelper::getServerData('REQUEST_METHOD');
        $method = HttpMethods::tryFrom($method ?? '');

        return $method ? $method : HttpMethods::GET;
    }

    /**
     * Get the current request path (normalized, no trailing slash except root).
     */
    public static function getPath(): string {
        return UrlHelper::getPath();
    }

    /**
     * Get a query parameter from $_GET, sanitized.
     */
    public static function getQueryParam(string $name): ?string {
        return DataRequesterHelper::getGetData($name);
    }

    /**
     * Get a POST parameter from $_POST, sanitized.
     */
    public static function getPostParam(string $name): ?string {
        return DataRequesterHelper::getPostData($name);
    }

    /**
     * Get a cookie value from $_COOKIE, sanitized.
     */
    public static function getCookie(string $name): ?string {
        return DataRequesterHelper::getCookieData($name);
    }

    /**
     * Get a file from $_FILES (returns the file array or null).
     *
     * @return ?array<mixed>
     */
    public static function getFile(string $name): ?array {
        return DataRequesterHelper::getFileData($name);
    }

    /**
     * Get all HTTP request headers as an associative array.
     *
     * @return array<string>
     */
    public static function getAllHeaders(): array {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$header] = (string)(DataRequesterHelper::processData($value) ?? '');
            }
        }
        if (function_exists('getallheaders')) {
            // Optionally merge with getallheaders() for completeness
            foreach (getallheaders() as $header => $value) {
                $headers[$header] = (string)(DataRequesterHelper::processData($value) ?? '');
            }
        }

        return $headers;
    }

    /**
     * Get the raw body of the HTTP request as a string.
     */
    public static function getBody(): string {
        return file_get_contents('php://input') ?: '';
    }

    /**
     * Get the client IP address from the request.
     */
    public static function getClientIP(): ?string {
        $ip = DataRequesterHelper::getServerData('HTTP_CLIENT_IP')
            ?? DataRequesterHelper::getServerData('HTTP_X_FORWARDED_FOR')
            ?? DataRequesterHelper::getServerData('REMOTE_ADDR');

        return $ip ?: null;
    }
}
