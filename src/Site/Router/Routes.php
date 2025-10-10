<?php

declare(strict_types=1);

namespace Site\Router;

use Guacamole\Http\Enums\HttpMethods;
use Guacamole\Router\RouteModel;
use Guacamole\Router\Router;
use Guacamole\Router\RouterSupport\Enums\FrontendFrameworks;
use Site\UI\Endpoints\Auth\WithGoogle\WithGoogle;
use Site\UI\Pages\Auth\Login\LoginPage;
use Site\UI\Pages\Dashboard\DashboardPage;
use Site\UI\Pages\Dynamic\DynamicPage;
use Site\UI\Pages\Home\HomePage;

class Routes {
    public static function home(): void {
        Router::register(
            new RouteModel(
                method: HttpMethods::GET,
                path: '/',
                controller: HomePage::class,
                framework: FrontendFrameworks::None,
            )
        );

        Router::register(
            new RouteModel(
                method: HttpMethods::GET,
                path: '/dynamic-route/:variable',
                controller: DynamicPage::class,
                framework: FrontendFrameworks::None,
            )
        );
    }

    public static function auth(): void {
        Router::register(
            new RouteModel(
                method: HttpMethods::GET,
                path: '/auth/login',
                controller: LoginPage::class,
                framework: FrontendFrameworks::None,
            ),
            new RouteModel(
                method: HttpMethods::GET,
                path: '/auth/google/callback',
                controller: WithGoogle::class,
                framework: FrontendFrameworks::None,
                params: [
                    'code' => 'googleAuthCode',
                ],
            )
        );
    }

    public static function dashboard(): void {
        Router::register(
            new RouteModel(
                method: HttpMethods::GET,
                path: '/dashboard',
                controller: DashboardPage::class,
                framework: FrontendFrameworks::Vue,
            )
        );
    }
}
