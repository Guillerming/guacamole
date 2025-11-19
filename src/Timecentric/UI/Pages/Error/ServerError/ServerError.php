<?php

declare(strict_types=1);

namespace Timecentric\UI\Pages\Error\ServerError;

use Guacamole\Helpers\DataRequesterHelper;
use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;
use Guacamole\UI\HeadData;
use Timecentric\UI\Layouts\Web;

class ServerError extends PageModel {
    private static ?string $message = null;

    public function __construct() {
        $status = DataRequesterHelper::getGetData('status');
        if (is_string($status) && strlen($status)) {
            $status = (int) $status;
            http_response_code($status);
        }

        $message = DataRequesterHelper::getGetData('code');
        if (is_string($message) && strlen($message)) {
            self::$message = $message;
        }
    }

    public static function useLayout(): LayoutModel {
        return new Web();
    }

    public static function getHeadData(): HeadData {
        return new HeadData(
            htmlTitle: 'Server Error',
            htmlDescription: "We couldn't process your request",
        );
    }

    public static function headHook(): void {
    }

    public static function footerHook(): void {
    }

    public static function html(): void { ?>

        <h1>500 - Server Error</h1>
        <p>We couldn't process your request. Try again in a few moments. Contact us if the problem persist.</p>

        <?php if (self::$message) { ?>
            <p><code><?php echo self::$message; ?></code></p>
        <?php } ?>

    <?php }
    }
