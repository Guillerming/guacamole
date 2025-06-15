<?php

declare(strict_types=1);

namespace Guacamole\Router;

use Guacamole\Helpers\UrlHelper;
use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Response;

class Router {
    /** @var RouteModel[] $routes */
    private static array $routes = [];

    public static function register(RouteModel $route): void {
        self::$routes[] = $route;
    }

    private static function getRoute(string $path): RouteModel {
        foreach (self::$routes as $route) {
            if ($route->path === $path) {
                return $route;
            }
        }

        // TODO: Dynamic urls
        // TODO: Subdirectories for SPAs (vue will control dashboard and dashboard/*)
        // TODO: 404, 410..
        // TODO: Redirections

        throw new \Exception("Route not found for path: {$path}");
    }

    public static function load(): void {
        $path = UrlHelper::getPath();
        $route = self::getRoute($path);
        // TODO: Headers and middlewares
        if (is_subclass_of($route->controller, EndpointModel::class)) {
            /** @var Response $response */
            $response = $route->controller::response();
            echo $response->print();
        } else {
            $route->controller::html();
        }
    }
}
