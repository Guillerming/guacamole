<?php

declare(strict_types=1);

namespace Site\UI\Pages\Dashboard;

use Guacamole\Helpers\HeaderSupport\Enum\Header;
use Guacamole\Http\Abstract\VuePageModel;

final class DashboardPage extends VuePageModel {
    public function __construct() {
        self::addHeader(Header::ContentTypeTextHtml);
    }
}