<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

use Guacamole\Helpers\HeaderSupport\Collection\HeaderCollection;
use Guacamole\Helpers\HeaderSupport\Enum\Header;

abstract class HttpResource {
    /**
     * Manage headers for the endpoint response.
     * This is a collection of headers that will be added to the response.
     * It is used to set headers like Content-Type, Cache-Control, etc.
     * */

    private static ?HeaderCollection $headers = null;

    private static function init(): void {
        if (!self::$headers) {
            self::$headers = new HeaderCollection();
        }
    }

    public static function addHeader(string|Header $header): void {
        self::init();
        self::$headers->add($header);
    }

    public static function getHeaders(): HeaderCollection {
        self::init();
        return self::$headers;
    }

    /**
     * Manage middlewares for the endpoint.
     * This is a collection of middleware classes that will be executed before the content is printed.
     * It is used to add functionality like authentication, logging, etc.
     * */

    /** @var array<int,string> $middlewares */
    private static array $middlewares = [];

    public static function addMiddleware(string $middlewareClassName): void {
        self::$middlewares[] = $middlewareClassName;
    }

    /**
     * @return array<int,string>
     */
    public static function getMiddlewares(): array {
        return self::$middlewares;
    }

    /**
     * Manage the HTTP status code for the endpoint response.
     * This is used to set the HTTP status code that will be returned to the client.
     * */

    private static int $status = 200;

    public static function setStatus(int $status): void {
        self::$status = $status;
    }

    public static function getStatus(): int {
        return self::$status;
    }
}