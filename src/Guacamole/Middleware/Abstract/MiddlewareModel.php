<?php

declare(strict_types=1);

namespace Guacamole\Middleware\Abstract;

abstract class MiddlewareModel {
    abstract public static function run(): void;
}