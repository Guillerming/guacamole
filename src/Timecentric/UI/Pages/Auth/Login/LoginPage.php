<?php

declare(strict_types=1);

namespace Timecentric\UI\Pages\Auth\Login;

use Guacamole\Config\Env;
use Guacamole\Helpers\CookieHelper;
use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\Models\Url;
use Guacamole\UI\HeadData;
use Timecentric\Enums\CookieNames;
use Timecentric\Helpers\RouteHelper;
use Timecentric\Middlewares\GuestMiddleware;
use Timecentric\Router\RouteIds;
use Timecentric\UI\Layouts\Web;

class LoginPage extends PageModel {
    private static ?string $state = null;

    public function __construct() {
        self::addMiddleware(GuestMiddleware::class);
        self::$state = bin2hex(random_bytes(16));

        CookieHelper::set(
            name: CookieNames::GoogleOAuthState,
            value: self::$state,
        );
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

    private static function getLoginWithGoogleLink(): string {
        $url = new Url(
            protocol: 'https',
            hostname: 'accounts.google.com',
            path: '/o/oauth2/v2/auth',
            params: [
                'client_id' => Env::get('GOOGLE_OAUTH_CLIENT_ID'),
                'redirect_uri' => RouteHelper::link(RouteIds::LoginCallback),
                'response_type' => 'code',
                'scope' => 'openid email profile',
                'state' => self::$state,
            ]
        );

        return $url->stringify();
    }

    public static function html(): void { ?>
        <h1>Login Page</h1>
        <a href="<?php echo self::getLoginWithGoogleLink(); ?>">Login with Google</a>
    <?php }
    }