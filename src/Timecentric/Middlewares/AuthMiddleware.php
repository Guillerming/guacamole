<?php

declare(strict_types=1);

namespace Timecentric\Middlewares;

use Guacamole\Helpers\UserHelper;
use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Abstract\HttpResource;
use Guacamole\Http\Response;
use Guacamole\Middleware\Abstract\MiddlewareModel;
use Timecentric\Helpers\RouteHelper;
use Timecentric\Router\RouteIds;

class AuthMiddleware extends MiddlewareModel {
    private static function isEndpoint(): ?Response {
        if (UserHelper::current()) {
            return null;
        }

        return new Response(
            status: 401,
            message: 'Unauthorized access',
            data: null,
        );
    }

    private static function isPage(): ?Response {
        if (UserHelper::current()) {
            return null;
        }

        return Response::redirect(RouteHelper::link(RouteIds::Login));
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