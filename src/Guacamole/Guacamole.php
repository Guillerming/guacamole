<?php

declare(strict_types=1);

namespace Guacamole;

use Dotenv\Dotenv;
use Guacamole\Database\Database;

if (file_exists(dirname(__DIR__, 2) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->load();
}

Database::connect();