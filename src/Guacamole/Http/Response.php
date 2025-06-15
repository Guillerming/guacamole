<?php

declare(strict_types=1);

namespace Guacamole\Http;

class Response {
    public function __construct(
        public int $status,
        public string $message,
        public mixed $data = null,
    ) {
    }

    public function print(): string {
        $response = [
            'message' => $this->message,
        ];
        if ($this->data) {
            $response['data'] = $this->data;
        }

        return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '';
    }
}