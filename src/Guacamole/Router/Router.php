<?php

declare(strict_types=1);

namespace Guacamole\Router;

use Guacamole\Helpers\UrlHelper;
use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\Http\Request;
use Guacamole\Middleware\Abstract\MiddlewareModel;
use Guacamole\Router\RouterSupport\Enums\FrontendFrameworks;

class Router {
    /** @var RouteModel[] $routes */
    private static array $routes = [];

    /** @var RouteModel $route. Current route instance. */
    private static ?RouteModel $route = null;

    /**
     * Checks if the current HTTP method matches the route's method.
     */
    private static function validateHttpMethod(RouteModel $route): bool {
        $requestMethod = Request::getHttpMethod();

        return $route->method === $requestMethod;
    }

    /**
     * Tries to find the registered route for the passed $path.
     * 
     * 
     */
    private static function findExactMatch(string $path): ?RouteModel {
        foreach (self::$routes as $route) {
            if ($route->path === $path && self::validateHttpMethod($route)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Tries to find the registered route for the passed $path.
     * 
     * 
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
            if ($matched && self::validateHttpMethod($route)) {
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
     * Tries to find a SPA route (Vue/React) whose path is a prefix of $path.
     * If found, returns a RouteModel with params['spaPath'] (if subruta existe).
     * 
     * 
     */
    private static function findSpaMatch(string $path): ?RouteModel {
        foreach (self::$routes as $route) {
            if ($route->framework !== FrontendFrameworks::None) {
                $base = rtrim($route->path, '/');
                if ($path === $base || str_starts_with($path, $base.'/')) {
                    if (!self::validateHttpMethod($route)) {
                        continue;
                    }
                    $spaPath = ltrim(substr($path, strlen($base)), '/');
                    $params = [];
                    if ($spaPath !== '') {
                        $params['spaPath'] = $spaPath;
                    }

                    return new RouteModel(
                        method: $route->method,
                        path: $route->path,
                        controller: $route->controller,
                        framework: $route->framework,
                        params: $params
                    );
                }
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
        $routeModel = self::findSpaMatch($path);
        if ($routeModel) {
            self::$route = $routeModel;

            return $routeModel;
        }

        // TODO: 404, 410..
        // TODO: Redirections

        throw new \Exception("Route not found for path: {$path}");
    }

    /**
     * @param RouteModel $route.
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

    private static function isEndpoint(EndpointModel $endpointModel): void {
        echo $endpointModel::response()->print();
    }

    private static function isPage(PageModel $pageModel): void {
        $layoutModel = $pageModel::useLayout();
        $layoutModel->html(
            pageModel: $pageModel,
        );
    }

    /**
     * Loads the controller associated to the current url path.
     * Runs the associated Headers and Middlewares.
     * Loads required resources such as layout.
     * Sends headers.
     * Wraps it all on a buffer to control output time.
     */
    public static function load(): void {
        $route = self::findOut();

        ob_start();

        $pageModel = new $route->controller();
        assert($pageModel instanceof PageModel || $pageModel instanceof EndpointModel);

        $middlewares = $pageModel->getMiddlewares();
        foreach ($middlewares as $middleware) {
            $middlewareHandler = new $middleware();
            assert($middlewareHandler instanceof MiddlewareModel);
            $response = $middlewareHandler::run($pageModel);
            if ($response !== null) {
                echo $response->print();
                ob_end_flush();

                return;
            }
        }

        if ($pageModel instanceof EndpointModel) {
            self::isEndpoint($pageModel);
        } else {
            self::isPage($pageModel);
        }

        $pageModel->getHeaders()->send();

        ob_end_flush();
    }
}
