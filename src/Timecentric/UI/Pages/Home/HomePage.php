<?php

declare(strict_types=1);

namespace Timecentric\UI\Pages\Home;

use Guacamole\Helpers\HeaderSupport\Enum\Header;
use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\UI\HeadData;
use Guacamole\UI\OpenGraphData;
use Timecentric\Middlewares\AuthMiddleware;
use Timecentric\UI\Layouts\Web;

class HomePage extends PageModel {
    public function __construct() {
        self::addHeader(Header::ContentTypeTextHtml);
        self::addMiddleware(AuthMiddleware::class);
    }

    public static function useLayout(): LayoutModel {
        return new Web();
    }

    public static function getHeadData(): HeadData {
        return new HeadData(
            htmlTitle: 'TimeCentric',
            htmlDescription: 'Log your activities',
            og: new OpenGraphData(
                title: 'Time register your activities',
                description: 'TimeCentric allows for time logging and analysis',
            ),
        );
    }

    public static function headHook(): void {
    }

    public static function footerHook(): void {
    }

    public static function html(): void { ?>
        <h1>TimeCentric</h1>
    <?php }
    }