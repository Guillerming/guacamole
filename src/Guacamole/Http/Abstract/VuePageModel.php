<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

use Guacamole\Helpers\DirHelper;

abstract class VuePageModel extends PageModel {
    /**
     * Reflected class names use "dist/" as a source folder, but for vite the source
     * folder is actually src. This means that vite output inside "public" dir contains
     * "src/" as a folder. Therefore we need to adjust the reflected filename to point
     * at dist/public/src/* instead of dist/public/dist/* in order to find all the
     * files compiled for Vue.
     * 
     */
    private static function convertReflectedFilename(string $reflectedFilename): string {
        $root = DirHelper::root();

        return str_replace("$root/dist", "$root/src", $reflectedFilename);
    }

    public static function html(): void {
        $reflectedFilename = (new \ReflectionClass(static::class))->getFileName() ?: '';
        $dir = dirname($reflectedFilename);
        $dir = self::convertReflectedFilename($dir);
        $root = DirHelper::root();
        $dir = str_replace($root, '', $dir);
        $dir = DirHelper::public("{$dir}/index.html");
        $html = file_get_contents($dir);
        echo $html;
    }
}