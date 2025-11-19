<?php

declare(strict_types=1);

namespace Timecentric;

use Guacamole\Router\Router;
use Timecentric\Router\Routes;

Routes::errors();
Routes::home();
Routes::auth();
Routes::dashboard();

Router::load();