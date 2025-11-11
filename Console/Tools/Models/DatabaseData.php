<?php

declare(strict_types=1);

namespace Console\Tools\Models;

use Guacamole\Config\Env;

class DatabaseData {
    public static function getHost(): string {
        $prop = Env::get('POSTGRES_HOST') ?: 'not set';
        assert(is_string($prop));

        return $prop;
    }

    public static function getPort(): string {
        $prop = Env::get('POSTGRES_PORT') ?: 'not set';
        assert(is_string($prop));

        return $prop;
    }

    public static function getName(): string {
        $prop = Env::get('POSTGRES_NAME') ?: 'not set';
        assert(is_string($prop));

        return $prop;
    }

    public static function getUser(): string {
        $prop = Env::get('POSTGRES_USER') ?: 'not set';
        assert(is_string($prop));

        return $prop;
    }

    public static function getPassword(): string {
        $prop = Env::get('POSTGRES_PASSWORD') ?: 'not set';
        assert(is_string($prop));

        return $prop;
    }
}