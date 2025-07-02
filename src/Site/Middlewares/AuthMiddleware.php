<?php

declare(strict_types=1);

namespace Site\Middlewares;

use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Abstract\HttpResource;
use Guacamole\Http\Response;
use Guacamole\Middleware\Abstract\MiddlewareModel;

class AuthMiddleware extends MiddlewareModel {
    private static function isEndpoint(): Response {
        return new Response(
            status: 401,
            message: 'Unauthorized access',
            data: null,
        );
    }

    private static function isPage(): Response {
        return Response::redirect('/login');
    }

    public static function run(HttpResource $page): ?Response {
        if ($page instanceof EndpointModel) {
            self::isEndpoint();
        } else {
            self::isPage();
        }

        return null;
    }
}