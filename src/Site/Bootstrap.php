<?php

declare(strict_types=1);

namespace Site;

use Guacamole\Router\Router;
use Site\Router\Routes;

Routes::home();
Routes::dashboard();

Router::load();