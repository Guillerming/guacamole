<?php

declare(strict_types=1);

namespace Guacamole\Http;

use Guacamole\Helpers\DataRequesterHelper;
use Guacamole\Helpers\UrlHelper;

class Request {
    /**
     * Get the HTTP method of the current request (GET, POST, etc).
     */
    public static function getHttpMethod(): string {
        $method = DataRequesterHelper::getServerData('REQUEST_METHOD');
        return $method ? strtoupper($method) : 'GET';
    }

    /**
     * Get the current request path (normalized, no trailing slash except root).
     */
    public static function getPath(): string {
        return UrlHelper::getPath();
    }

    // Puedes añadir más métodos útiles aquí, por ejemplo:
    // public static function getQueryParam(string $name): ?string { ... }
    // public static function getAllHeaders(): array { ... }
    // public static function getBody(): string { ... }
    // public static function getClientIp(): string { ... }
}
