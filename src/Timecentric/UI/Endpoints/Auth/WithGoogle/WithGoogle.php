<?php

declare(strict_types=1);

namespace Timecentric\UI\Endpoints\Auth\WithGoogle;

use Guacamole\Helpers\DataRequesterHelper;
use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Response;

class WithGoogle extends EndpointModel {
    public static function response(EndpointModel $endpointModel): Response {
        return new Response(
            status: 200,
            message: 'Testing response',
            data: [
                'state-coo' => DataRequesterHelper::getCookieData('google-oauth-state'),
                'state-url' => DataRequesterHelper::getGetData('state'),
                'code' => DataRequesterHelper::getGetData('code'),
                'scope' => DataRequesterHelper::getGetData('scope'),
                'authuser' => DataRequesterHelper::getGetData('authuser'),
                'prompt' => DataRequesterHelper::getGetData('prompt'),
            ]
        );
    }
}