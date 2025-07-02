<?php

declare(strict_types=1);

namespace Guacamole\UI;

class OpenGraphData {
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $imageUrl = null,
        public ?string $type = null,
        public ?string $url = null,
        public ?string $siteName = null,
        public ?string $locale = null,
    ) {
    }

    public function printOpenGraphData(): void { ?>
        <?php if ($this->title) { ?>
            <meta property="og:title" content="Introduction to Open Graph Protocol">
        <?php } ?>

        <?php if ($this->description) { ?>
            <meta property="og:description" content="Learn how the Open Graph protocol enhances social media sharing by controlling content display on platforms like Facebook and LinkedIn.">
        <?php } ?>

        <?php if ($this->type) { ?>
            <meta property="og:type" content="article">
        <?php } ?>

        <?php if ($this->imageUrl) { ?>
            <meta property="og:image" content="https://www.example.com/images/article-preview.jpg">
        <?php } ?>

        <?php if ($this->url) { ?>
            <meta property="og:url" content="https://www.example.com/blog/open-graph-introduction">
        <?php } ?>

        <?php if ($this->siteName) { ?>
            <meta property="og:site_name" content="My Awesome Blog">
        <?php } ?>

        <?php if ($this->locale) { ?>
            <meta property="og:locale" content="en_US"> 
        <?php } ?>

    <?php }
    }