<?php

declare(strict_types=1);

namespace Timecentric\UI\Pages\Auth\Login;

use Guacamole\Config\AppConfig;
use Guacamole\Config\Env;
use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\Models\Url;
use Guacamole\UI\HeadData;
use Timecentric\UI\Layouts\Web;

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

    private static function getLoginWithGoogleLink(): string {
        $state = bin2hex(random_bytes(16));
        setcookie(
            name: 'google-oauth-state',
            value: $state,
            expires_or_options: time() + 60 * 5,
            path: '/',
            secure: true,
            httponly: true,
        );

        $url = new Url(
            protocol: 'https',
            hostname: 'accounts.google.com',
            path: '/o/oauth2/v2/auth',
            params: [
                'client_id' => Env::get('GOOGLE_OAUTH_CLIENT_ID'),
                'redirect_uri' => AppConfig::baseUrl('/auth/google/callback'),
                'response_type' => 'code',
                'scope' => 'openid%20email%20profile',
                'state' => $state,
            ]
        );

        return $url->stringify();
    }

    public static function html(): void { ?>
        <h1>Login Page</h1>
        <a href="<?php echo self::getLoginWithGoogleLink(); ?>">Login with Google</a>
    <?php }
    }