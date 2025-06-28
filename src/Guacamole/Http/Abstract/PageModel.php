<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

abstract class PageModel extends HttpResource {
    abstract public static function html(): void;
}