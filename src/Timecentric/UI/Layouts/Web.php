<?php

declare(strict_types=1);

namespace Timecentric\UI\Layouts;

use Guacamole\Http\Abstract\LayoutModel;
use Guacamole\Http\Abstract\PageModel;

class Web extends LayoutModel {
    public function html(PageModel $pageModel): void { ?>

        <!DOCTYPE html>

        <html lang="en">

            <head>
                <meta charset="UTF-8">
                <?php $pageModel->getHeadData()->print(); ?>
                <?php $pageModel->headHook(); ?>
                <?php $pageModel->includeStyles(); ?>
            </head>

            <body>

                <main id="main">
                    <?php $pageModel->html(); ?>
                </main>

                <?php $pageModel->footerHook(); ?>
                <?php $pageModel->includeScripts(); ?>
            </body>

        </html>
    <?php }
    }
