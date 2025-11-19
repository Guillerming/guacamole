<?php

declare(strict_types=1);

namespace Guacamole\Config;

use Guacamole\Models\Url;

class AppConfig {
    const GuacamoleVersion = '0.0.0-rc1';
    const ProjectVersion = '0.0.0';

    /**
     * 
     * @param ?string             $append. Default null.
     * @param array<string,mixed> $params. Default [].
     */
    static function baseUrl(?string $append = null, array $params = []): Url {
        $port = Env::get('NGINX_EXTERNAL_PORT');
        if (gettype($port) != 'string') {
            $port = null;
        }
        $port = (int) $port;

        $hostname = Env::get('HTTPS_HOSTNAME');
        assert(gettype($hostname) == 'string');

        $url = new Url(
            protocol: 'https',
            hostname: $hostname,
            port: $port,
            path: $append,
            params: $params
        );

        return $url;
    }
}