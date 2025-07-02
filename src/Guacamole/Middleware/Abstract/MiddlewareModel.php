<?php

declare(strict_types=1);

namespace Guacamole\Middleware\Abstract;

use Guacamole\Http\Abstract\HttpResource;
use Guacamole\Http\Response;

abstract class MiddlewareModel {
    /**
     * Middleware can return a Response to interrupt the flow (redirect, error, etc), or null to continue.
     */
    abstract public static function run(HttpResource $page): ?Response;
}