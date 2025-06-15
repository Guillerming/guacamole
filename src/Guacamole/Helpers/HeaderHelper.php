<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

use Guacamole\Helpers\HeaderSupport\Collection\HeaderCollection;
use Guacamole\Helpers\HeaderSupport\Enum\Header;

class HeaderHelper {
    private static ?HeaderHelper $instance = null;
    private static HeaderCollection $collection = new HeaderCollection();

    private static function init(): HeaderHelper {
        if (self::$instance === null) {
            self::$instance = new HeaderHelper();
        }

        return self::$instance;
    }

    public function set(string|Header $header): void {
        self::init();
        self::$collection->add($header);
    }

    public function get(): HeaderCollection {
        self::init();

        return self::$collection;
    }

    public function send(): void {
        self::init();
        foreach (self::$collection->headers as $header) {
            header($header);
        }
    }
}
