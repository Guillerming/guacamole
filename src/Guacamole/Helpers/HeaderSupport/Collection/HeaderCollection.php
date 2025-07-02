<?php

declare(strict_types=1);

namespace Guacamole\Helpers\HeaderSupport\Collection;

use Guacamole\Helpers\HeaderSupport\Enum\Header;

class HeaderCollection {
    /** @var array<int,string> $headers */
    public array $headers = [];

    public function construct(
        string|Header ...$headers,
    ): void {
        foreach ($headers as $header) {
            $this->add($header);
        }
    }

    public function add(string|Header $header): self {
        if ($header instanceof Header) {
            $header = $header->value;
        }
        $this->headers[] = $header;

        return $this;
    }

    public function send(): void {
        if (headers_sent()) {
            return;
        }
        foreach ($this->headers as $header) {
            header($header);
        }
    }
}