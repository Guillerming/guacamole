<?php

declare(strict_types=1);

namespace Guacamole\Router;

use Guacamole\Router\RouterSupport\Enums\FrontendFrameworks;

class RouteModel {
    public function __construct(
        public string $method,
        public string $path,
        public string $controller,
        public FrontendFrameworks $framework = FrontendFrameworks::None,
    ) {
    }
}
