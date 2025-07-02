<?php

declare(strict_types=1);

namespace Site\UI\Pages\Dynamic;

use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\Router\RouteModel;
use Guacamole\Router\Router;
use Guacamole\UI\HeadData;
use Site\UI\Layouts\Web;

class DynamicPage extends PageModel {
    private static RouteModel $route;

    public function __construct() {
        self::$route = Router::get();
    }

    public static function useLayout(): LayoutModel {
        return new Web();
    }

    public static function getHeadData(): HeadData {
        return new HeadData(
            htmlTitle: 'This is a dynamic page',
            htmlDescription: 'Dynamic pages allow variables to be passed on url and to retrieve them easily in the page',
        );
    }

    public static function headHook(): void {
    }

    public static function footerHook(): void {
    }

    public static function html(): void { ?>
        <h1>Dynamic page</h1>
        <p>Url variables are easy to retrieve using Guacamole\Router\Router::get()->getParam('nameOfVariable');</p>
        <?php if (count(self::$route->getParams())) { ?>
            <p>Variable: <?php echo self::$route->getParam('variable'); ?></p>
        <?php } ?>
    <?php }
    }