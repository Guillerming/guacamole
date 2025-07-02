<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

abstract class LayoutModel {
    /**
     * Render the layout, receiving callbacks for content, head, and footer.
     */
    abstract public function html(PageModel $pageModel): void;
}
