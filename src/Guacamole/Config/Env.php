<?php

namespace Guacamole\Config;

class Env {

    public static function get(string $key): mixed {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return null;
    }

}