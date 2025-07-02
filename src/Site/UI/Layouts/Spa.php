<?php

declare(strict_types=1);

namespace Site\UI\Layouts;

use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;

class Spa extends LayoutModel {
    /**
     * Layout in SPAs are empty since it's the framework itself (Vue/React)
     * the one who handles the page contents.
     * Update the index.html in your SPA directory to meet your needs.
     */
    public function html(PageModel $pageModel): void {
        $pageModel->html();
    }
}
