<?php

declare(strict_types=1);

namespace Guacamole\UI;

class HeadData {
    public function __construct(
        public string $htmlTitle,
        public string $htmlDescription,
        public ?OpenGraphData $og = null,
    ) {
    }

    public function print(): void { ?>
        <title><?php echo $this->htmlTitle; ?></title>
        <meta name="description" content="<?php echo $this->htmlDescription; ?>">
        <?php if ($this->og) {
            $this->og->printOpenGraphData();
        } ?>

    <?php }
    }