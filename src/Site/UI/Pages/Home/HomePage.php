<?php

declare(strict_types=1);

namespace Site\UI\Pages\Home;

use Guacamole\Http\Abstract\PageModel;

class HomePage extends PageModel {
    public static function html(): void { ?>
        Home page
    <?php }
}