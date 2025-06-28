<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

use Guacamole\Helpers\DirHelper;

abstract class PageModel extends HttpResource {
    abstract public static function html(): void;

    /**
     * Scans the public/assets/pages/{PageName}/ directory and
     * prints <link> and <script> tags for all CSS and JS assets.
     * Ignores .map files. Call this in your page's html() method.
     */
    public static function includeAssets(): void {
        $class = static::class;
        $parts = explode('\\', $class);
        $pageName = $parts[count($parts) - 2];
        $publicPath = "/assets/pages/{$pageName}/";
        $assetsDir = DirHelper::public($publicPath);
        if (!is_dir($assetsDir)) {
            return;
        }
        $files = scandir($assetsDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || str_ends_with($file, '.map')) {
                continue;
            }
            if (str_ends_with($file, '.css')) {
                echo "<link rel=\"stylesheet\" href=\"{$publicPath}{$file}\">\n";
            } elseif (str_ends_with($file, '.js')) {
                echo "<script type=\"module\" src=\"{$publicPath}{$file}\"></script>\n";
            }
        }
    }
}