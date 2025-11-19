<?php

declare(strict_types=1);

namespace Timecentric\UI\Pages\Dashboard;

use Guacamole\Helpers\HeaderSupport\Enum\Header;
use Guacamole\Http\Abstract\VuePageModel;
use Timecentric\Middlewares\AuthMiddleware;

final class DashboardPage extends VuePageModel {
    public function __construct() {
        self::addHeader(Header::ContentTypeTextHtml);
        self::addMiddleware(AuthMiddleware::class);
    }
}