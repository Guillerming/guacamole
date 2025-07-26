<?php

declare(strict_types=1);

namespace Site\UI\Endpoints\Auth\Login;

use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Response;

class Token extends EndpointModel {
    public static function response(EndpointModel $endpoint): Response {
        return new Response(
            status: 200,
            message: '',
            data: []
        );
    }
}