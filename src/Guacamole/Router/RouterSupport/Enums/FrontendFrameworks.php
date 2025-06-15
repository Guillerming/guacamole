<?php

declare(strict_types=1);

namespace Guacamole\Router\RouterSupport\Enums;

enum FrontendFrameworks: string {
    case Vue = 'vue';
    case React = 'react';
    case None = 'none';
}