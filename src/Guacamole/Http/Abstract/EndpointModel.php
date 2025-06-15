<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

use Guacamole\Http\Response;

abstract class EndpointModel extends HttpResource {
    /**
     * This method is called to initialize the endpoint.
     * It is used to set up headers, middlewares, and other configurations.
     * */
    abstract public static function init(): void;

    /**
     * This method is called to get the response of the endpoint.
     * */
    abstract public static function response(): Response;
}