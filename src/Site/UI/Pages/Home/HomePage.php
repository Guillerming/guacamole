<?php

declare(strict_types=1);

namespace Site\UI\Pages\Home;

use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\UI\HeadData;
use Guacamole\UI\OpenGraphData;
use Site\UI\Layouts\Web;

class HomePage extends PageModel {
    public static function useLayout(): LayoutModel {
        return new Web();
    }

    public static function getHeadData(): HeadData {
        return new HeadData(
            htmlTitle: 'Home page',
            htmlDescription: 'The business of the history',
            og: new OpenGraphData(
                title: 'The bizz',
                description: 'Awesome biz',
            ),
        );
    }

    public static function headHook(): void {
    }

    public static function footerHook(): void {
    }

    public static function html(): void { ?>
        <h1>Home Page</h1>
    <?php }
    }