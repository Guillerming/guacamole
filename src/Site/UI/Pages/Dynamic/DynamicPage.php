<?php

declare(strict_types=1);

namespace Site\UI\Pages\Dynamic;

use Guacamole\Http\Abstract\PageModel;
use Guacamole\Router\RouteModel;
use Guacamole\Router\Router;

class DynamicPage extends PageModel {

    private static RouteModel $route;

    public function __construct() {
        self::$route = Router::get();
    }

    public static function html(): void { ?>
        Dynamic page
        <?php if (count(self::$route->getParams())) { ?>
            <p>Variable: <?php echo self::$route->getParam('variable'); ?></p>
        <?php } ?>
    <?php }
}