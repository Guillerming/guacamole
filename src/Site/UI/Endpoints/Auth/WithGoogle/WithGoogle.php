<?php

declare(strict_types=1);

namespace Site\UI\Endpoints\Auth\WithGoogle;

use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Response;

class WithGoogle extends EndpointModel {
    public static function response(EndpointModel $endpoint): Response {
        return new Response(
            status: 200,
            message: '',
            data: []
        );
    }
}