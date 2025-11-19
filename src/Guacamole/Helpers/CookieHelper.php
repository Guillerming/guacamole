<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

use Guacamole\Config\AppConfig;
use Timecentric\Enums\CookieNames;

class CookieHelper {
    private static ?CookieHelper $instance = null;

    private static string $domain;

    private static function init(): void {
        if (self::$instance) {
            return;
        }
        self::$instance = new self();
        self::getDomain();
    }

    private static function getDomain(): void {
        $url = AppConfig::baseUrl();
        $hostname = $url->getHostname();
        $hostname = explode('.', $hostname);
        self::$domain = ".{$hostname[count($hostname)-2]}.{$hostname[count($hostname)-1]}";
    }

    public static function set(CookieNames $name, string $value, int $duration = 3600 * 24): bool {
        self::init();

        return setcookie(
            name: $name->value,
            value: $value,
            expires_or_options: time() + $duration,
            path: '/',
            domain: self::$domain,
            secure: true,
            httponly: true,
        );
    }

    public static function delete(CookieNames $name): bool {
        self::init();

        return self::set(
            name: $name,
            value: '',
            duration: (3600 * 24) * -1
        );
    }
}