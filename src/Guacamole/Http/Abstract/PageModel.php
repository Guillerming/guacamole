<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

use Guacamole\Helpers\DirHelper;
use Guacamole\UI\HeadData;

abstract class PageModel extends HttpResource {
    abstract public static function html(): void;

    /**
     * Require pages to specify their layout as an instance of LayoutModel.
     */
    abstract public static function useLayout(): LayoutModel;

    /**
     * Require pages to specify head data such as title, description, og..
     */
    abstract public static function getHeadData(): HeadData;

    /**
     * Require pages to specify content for Head hook
     */
    abstract public static function headHook(): void;

    /**
     * Require pages to specify content for Footer hook
     */
    abstract public static function footerHook(): void;

    /**
     * Scans the public/assets/pages/{PageName}/ directory and
     * finds all CSS/JS assets. Ignores .map files.
     * 
     * @param string $extension: js | css
     * 
     * @return array<int,string> $assets
     */
    private static function findAssets(string $extension): array {
        $assets = [];
        $class = static::class;
        $parts = explode('\\', $class);
        $pageName = $parts[count($parts) - 2];
        $publicPath = "/assets/pages/{$pageName}/";
        $assetsDir = DirHelper::public($publicPath);
        if (!is_dir($assetsDir)) {
            return $assets;
        }
        $files = scandir($assetsDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || str_ends_with($file, '.map')) {
                continue;
            }
            if (str_ends_with($file, ".{$extension}")) {
                $assets[] = $publicPath.$file;
            }
        }

        return $assets;
    }

    /**
     * Scans the public/assets/pages/{PageName}/ directory and
     * prints <link> tags for all CSS assets. Ignores .map files.
     * Call this in any layout's html() method.
     */
    public static function includeStyles(): void {
        $assets = self::findAssets('css');
        foreach ($assets as $file) { ?>
            <link rel="stylesheet" href="<?php echo $file; ?>">
        <?php }
        }

    /**
     * Scans the public/assets/pages/{PageName}/ directory and
     * prints <script> tags for all JS assets. Ignores .map files.
     * Call this in any layout's html() method.
     */
    public static function includeScripts(): void {
        $assets = self::findAssets('js');
        foreach ($assets as $file) { ?>
            <script type="module" src="<?php echo $file; ?>"></script>
        <?php }
        }
}