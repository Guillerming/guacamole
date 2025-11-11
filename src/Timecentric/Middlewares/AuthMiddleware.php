<?php

declare(strict_types=1);

namespace Timecentric\Middlewares;

use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Abstract\HttpResource;
use Guacamole\Http\Response;
use Guacamole\Middleware\Abstract\MiddlewareModel;

class AuthMiddleware extends MiddlewareModel {
    private static bool $forceNotLoggedIn = false; // TODO: Delete after testing

    private static function isEndpoint(): ?Response {
        // TODO: Create the actual conditions
        if (!self::$forceNotLoggedIn) {
            return null;
        }

        return new Response(
            status: 401,
            message: 'Unauthorized access',
            data: null,
        );
    }

    private static function isPage(): ?Response {
        // TODO: Create the actual conditions
        if (!self::$forceNotLoggedIn) {
            return null;
        }

        return Response::redirect('/auth/login');
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