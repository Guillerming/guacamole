<?php

declare(strict_types=1);

namespace Timecentric\Middlewares;

use Guacamole\Helpers\UserHelper;
use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Abstract\HttpResource;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\Http\Response;
use Guacamole\Middleware\Abstract\MiddlewareModel;
use Timecentric\Helpers\RouteHelper;
use Timecentric\Router\RouteIds;

class GuestMiddleware extends MiddlewareModel {
    private static function isEndpoint(): ?Response {
        if (!UserHelper::current()) {
            return null;
        }

        return new Response(
            status: 401,
            message: 'Only guests',
            data: null,
        );
    }

    private static function isPage(): ?Response {
        if (UserHelper::current()) {
            return Response::redirect(RouteHelper::link(RouteIds::Dashboard));
        }

        return null;
    }

    public static function run(HttpResource $page): ?Response {
        if ($page instanceof EndpointModel) {
            return self::isEndpoint();
        }
        if ($page instanceof PageModel) {
            return self::isPage();
        }

        return null;
    }
}
