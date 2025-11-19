<?php

declare(strict_types=1);

namespace Timecentric\Helpers;

use Guacamole\Config\AppConfig;
use Timecentric\Router\RouteIds;

class RouteHelper {
    /**
     * @param RouteIds            $route.  The route id to link to.
     * @param ?string             $append. Default null.
     * @param array<string,mixed> $params. Default [].
     */
    public static function link(RouteIds $route, ?string $append = null, array $params = []): string {
        $path = $route->value;
        if ($append) {
            $path .= $append;
        }

        return AppConfig::baseUrl(
            append: $path,
            params: $params,
        )->stringify();
    }
}