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

    /**
     * Create a redirect response.
     */
    public static function redirect(string $url, int $status = 302): self {
        http_response_code($status);
        header('Location: ' . $url);

        return new self($status, 'Redirecting', null);
    }
}