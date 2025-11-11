<?php

declare(strict_types=1);

namespace Guacamole\Config;

use Guacamole\Helpers\StringHelper;

class AppConfig {
    static function baseUrl(?string $append = null): string {
        $port = Env::get('HTTPS_PORT');
        assert(gettype($port) == 'string');
        if (strlen($port)) {
            $port = ":{$port}";
        }
        $hostname = Env::get('HTTPS_HOSTNAME');
        assert(gettype($hostname) == 'string');
        $baseUrl = "https://{$hostname}{$port}/";
        if ($append) {
            $baseUrl .= $append;
        }

        return StringHelper::mergeSlashes($baseUrl);
    }

    const GuacamoleVersion = '0.0.0-rc1';
    const ProjectVersion = '0.0.0';
}