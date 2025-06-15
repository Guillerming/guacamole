<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

class DirHelper {
    private static string $root;

    private static function init(): void {
        self::$root = realpath(__DIR__ . '/../../../') ?: '';
    }

    public static function root(): string {
        self::init();

        return self::$root;
    }

    public static function src(): string {
        self::init();

        return self::$root.'/dist';
    }

    public static function guacamole(string $append = '/'): string {
        self::init();

        return StringHelper::mergeSlashes(rtrim(self::$root."/dist/Guacamole{$append}", '/'));
    }

    public static function public(string $append = '/'): string {
        self::init();

        return StringHelper::mergeSlashes(rtrim(self::$root."/dist/public{$append}", '/'));
    }

    public static function project(string $append = '/'): string {
        self::init();

        return StringHelper::mergeSlashes(rtrim(self::$root."/dist/Site{$append}", '/'));
    }
}