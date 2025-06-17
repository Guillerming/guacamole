<?php

declare(strict_types=1);

namespace Guacamole\Router;

use Guacamole\Router\RouterSupport\Enums\FrontendFrameworks;

class RouteModel {
    /**
     * @param string $method.
     * @param string $path.
     * @param string $controller.
     * @param FrontendFrameworks $framework. Default FrontendFrameworks::None.
     * @param array<string,string> $params. Default [].
     */
    public function __construct(
        public string $method,
        public string $path,
        public string $controller,
        public FrontendFrameworks $framework = FrontendFrameworks::None,
        private array $params = [],
    ) {
    }

    /**
     * Returns all the params.
     */
    public function getParams(): array {
        return $this->params;
    }

    /**
     * Returns one specific param value, if available, or null.
     */
    public function getParam(string $key): ?string {
        if (!isset($this->params[$key])) {
            return null;
        }
        return $this->params[$key];
    }
}
