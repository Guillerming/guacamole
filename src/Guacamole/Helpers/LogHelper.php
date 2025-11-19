<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class LogHelper {
    public static function d(mixed $data): void {
        if (gettype($data) != 'string') {
            $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        echo '<pre>'.$data.'</pre>';
    }

    public static function dd(mixed $data): void {
        self::d($data);
        exit;
    }

    public static function vd(mixed $data): void {
        var_dump($data);
    }

    public static function vdd(mixed $data): void {
        self::vd($data);
        exit;
    }

    /**
     * Log message to stdout with optional data array
     *
     * @param array<mixed>|null $data
     */
    public static function log(string $message, ?array $data = null): void {
        $output = $message;
        if ($data !== null) {
            $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $output .= ' ' . $jsonData;
        }

        $stream = fopen('php://stdout', 'w');
        assert(gettype($stream) == 'resource');
        fwrite($stream, $output . "\n");
    }

    /**
     * Log error to stderr with optional data array
     *
     * @param array<mixed>|null $data
     */
    public static function error(string $message, ?array $data = null): void {
        $output = $message;
        if ($data !== null) {
            $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $output .= ' ' . $jsonData;
        }

        $stream = fopen('php://stderr', 'w');
        assert(gettype($stream) == 'resource');
        fwrite($stream, $output . "\n");
    }
}