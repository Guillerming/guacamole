<?php

declare(strict_types=1);

namespace Site\UI\Pages\Auth\Login;

use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\UI\HeadData;
use Site\UI\Layouts\Web;

class LoginPage extends PageModel {
    public function __construct() {
    }

    public static function useLayout(): LayoutModel {
        return new Web();
    }

    public static function getHeadData(): HeadData {
        return new HeadData(
            htmlTitle: 'Login page',
            htmlDescription: 'Login into your account',
        );
    }

    public static function headHook(): void {
    }

    public static function footerHook(): void {
    }

    public static function html(): void { ?>
        <h1>Login Page</h1>
    <?php }
    }