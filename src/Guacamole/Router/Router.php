<?php

declare(strict_types=1);

namespace Guacamole\Router;

use Guacamole\Helpers\UrlHelper;
use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Response;

class Router {
    /** @var RouteModel[] $routes */
    private static array $routes = [];

    /** @var RouteModel $route. Current route instance. */
    private static ?RouteModel $route = null;

    /**
     * @param string $path
     * 
     * Tries to find the registered route for the passed $path.
     */
    private static function findExactMatch(string $path): ?RouteModel {
        foreach (self::$routes as $route) {
            if ($route->path === $path) {
                return $route;
            }
        }
        return null;
    }

    /**
     * @param string $path
     * 
     * Tries to find the registered route for the passed $path.
     */
    private static function findDynamicMatch(string $path): ?RouteModel {
        $pathSegments = explode('/', trim($path, '/'));
        foreach (self::$routes as $route) {
            $routeSegments = explode('/', trim($route->path, '/'));
            if (count($routeSegments) !== count($pathSegments)) {
                continue;
            }
            $matched = true;
            $params = [];
            for ($i = 0; $i < count($routeSegments); $i++) {
                if (str_starts_with($routeSegments[$i], ':')) {
                    $key = substr($routeSegments[$i], 1);
                    $params[$key] = $pathSegments[$i];
                    continue;
                }
                if ($routeSegments[$i] !== $pathSegments[$i]) {
                    $matched = false;
                    break;
                }
            }
            if ($matched) {
                return new RouteModel(
                    method: $route->method,
                    path: $route->path,
                    controller: $route->controller,
                    framework: $route->framework,
                    params: $params
                );
            }
        }
        return null;
    }

    /**
     * Tries to find the registered route for the url $path.
     */
    private static function findOut(): RouteModel {
        $path = UrlHelper::getPath();
        $routeModel = self::findExactMatch($path);
        if ($routeModel) {
            self::$route = $routeModel;
            return $routeModel;
        }
        $routeModel = self::findDynamicMatch($path);
        if ($routeModel) {
            self::$route = $routeModel;
            return $routeModel;
        }

        // TODO: Subdirectories for SPAs (vue will control dashboard and dashboard/*)
        // TODO: 404, 410..
        // TODO: Redirections
        // TODO: Filter by method

        throw new \Exception("Route not found for path: {$path}");
    }

    /**
     * @param Routemodel $route.
     * 
     * Registers a route in the system.
     */
    public static function register(RouteModel $route): void {
        self::$routes[] = $route;
    }

    /**
     * Gets the current route instance.
     */
    public static function get(): RouteModel {
        if (!self::$route) {
            return self::findOut();
        }
        return self::$route;
    }

    /**
     * Loads the controller associated to the current url path.
     * Runs the associated Headers and Middlewares.
     */
    public static function load(): void {
        $route = self::findOut();
        new $route->controller();
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
