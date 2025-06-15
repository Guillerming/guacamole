<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

use Guacamole\Http\Abstract\HttpResource;

abstract class PageModel extends HttpResource {
    abstract public static function html(): void;
}