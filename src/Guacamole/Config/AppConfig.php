<?php

declare(strict_types=1);

namespace Guacamole\Config;

use Guacamole\Helpers\StringHelper;

class AppConfig {
    static function baseUrl(?string $append = null): string {
        $httpsPort = Env::get('HTTPS_PORT');
        assert(gettype($httpsPort) == 'string');
        $baseUrl = 'https://localhost:'.$httpsPort;
        if ($append) {
            $baseUrl .= $append;
        }

        return StringHelper::mergeSlashes($baseUrl);
    }

    const GuacamoleVersion = '0.0.0-rc1';
    const ProjectVersion = '0.0.0';
}