<?php

declare(strict_types=1);

namespace Timecentric\Router;

use Guacamole\Http\Enums\HttpMethods;
use Guacamole\Router\RouteModel;
use Guacamole\Router\Router;
use Guacamole\Router\RouterSupport\Enums\FrontendFrameworks;
use Timecentric\UI\Endpoints\Auth\WithGoogle\WithGoogle;
use Timecentric\UI\Pages\Auth\Login\LoginPage;
use Timecentric\UI\Pages\Dashboard\DashboardPage;
use Timecentric\UI\Pages\Dynamic\DynamicPage;
use Timecentric\UI\Pages\Home\HomePage;

class Routes {
    public static function home(): void {
        Router::register(
            new RouteModel(
                method: HttpMethods::GET,
                path: RouteIds::Home->value,
                controller: HomePage::class,
                framework: FrontendFrameworks::None,
            )
        );

        Router::register(
            new RouteModel(
                method: HttpMethods::GET,
                path: RouteIds::DynamicRoute->value,
                controller: DynamicPage::class,
                framework: FrontendFrameworks::None,
            )
        );
    }

    public static function auth(): void {
        Router::register(
            new RouteModel(
                method: HttpMethods::GET,
                path: RouteIds::Login->value,
                controller: LoginPage::class,
                framework: FrontendFrameworks::None,
            ),
            new RouteModel(
                method: HttpMethods::GET,
                path: RouteIds::LoginCallback->value,
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
                path: RouteIds::Dashboard->value,
                controller: DashboardPage::class,
                framework: FrontendFrameworks::Vue,
            )
        );
    }
}
