<?php

declare(strict_types=1);

namespace Guacamole\Models;

class Url {
    /**
     * @param string               $protocol. https, http...
     * @param string               $hostname. domain.com
     * @param ?int                 $port.     Null if none, else: 8080..
     * @param ?string              $path.     Null if none.
     * @param ?array<string,mixed> $params.   Null if none, else: the query params.
     */
    public function __construct(
        private string $protocol,
        private string $hostname,
        private ?int $port = null,
        private ?string $path = null,
        private ?array $params = null,
    ) {
        $this->hostname = rtrim($this->hostname, '/');
        $this->decodeParamValues();
    }

    private function decodeParamValues(): void {
        if (!$this->params) {
            return;
        }
        foreach ($this->params as $key => $value) {
            if (is_null($value)) {
                $this->params[$key] = null;
            } else {
                $this->params[$key] = gettype($value) == 'string' ? urldecode($value) : $value;
            }
        }
    }

    /**
     * Returns the full url
     * 
     */
    public function stringify(): string {
        $url = "{$this->protocol}://{$this->hostname}";
        if ($this->port) {
            $url .= ":{$this->port}";
        }
        if ($this->path) {
            $url .= '/'.ltrim($this->path, '/');
        }
        if ($this->params) {
            $url .= $this->getQueryString();
        }

        return $url;
    }

    /**
     * Adds a param to the Url model
     * 
     * @param string                     $key.   The name of the param.
     * @param string|int|float|null|bool $value. Optional. Use null to add only key.
     * 
     */
    public function addParam(string $key, string|int|float|null|bool $value): self {
        $this->params[$key] = $value;
        $this->decodeParamValues();

        return $this;
    }

    /**
     * Get specific param from the url if found.
     * Returns null if not found.
     * Returns empty string if found without value.
     * 
     * @param string $key. The name of the param to find.
     * 
     */
    public function getParam(string $key): mixed {
        if (!is_array($this->params)) {
            return null;
        }
        if (array_key_exists($key, $this->params)) {
            $value = $this->params[$key];
            if (is_null($value)) {
                $value = '';
            }
            if ($value == 'true' || $value == 'false') {
                $value = $value == 'true' ? true : false;
            }

            return $value;
        }

        return null;
    }

    /**
     * Gets all the params from the url as an array.
     * 
     * @return null|array<string,mixed>
     */
    public function getParams(): ?array {
        return $this->params;
    }

    /**
     * Returns the params as a string. Includes the question mark.
     * If no params, returns empty string.
     * 
     */
    public function getQueryString(): string {
        if (!$this->params) {
            return '';
        }

        $string = '?';

        foreach ($this->params as $key => $value) {
            $param = $key;
            if (is_string($value)) {
                $treatedValue = urlencode($value);
            } else if (is_float($value) || is_int($value)) {
                $treatedValue = $value;
            } else {
                $treatedValue = null;
            }
            if (!is_null($treatedValue)) {
                $param .= '='.$treatedValue;
            }
            $string .= $param.'&';
        }

        $string = rtrim($string, '&');

        return $string;
    }
}