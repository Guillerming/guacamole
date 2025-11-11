<?php

declare(strict_types=1);

namespace Timecentric\UI\Pages\NotFound;

use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\UI\HeadData;
use Timecentric\UI\Layouts\Web;

class NotFound extends PageModel {
    public function __construct() {
    }

    public static function useLayout(): LayoutModel {
        return new Web();
    }

    public static function getHeadData(): HeadData {
        return new HeadData(
            htmlTitle: 'Not Found page',
            htmlDescription: "We couldn't find the requested resource",
        );
    }

    public static function headHook(): void {
    }

    public static function footerHook(): void {
    }

    public static function html(): void {
        http_response_code(404); ?>

        <h1>404 - Page Not Found</h1>
        <p>The page you are looking for does not exist.</p>

    <?php }
    }
