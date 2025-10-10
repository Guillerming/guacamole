<?php

declare(strict_types=1);

namespace Guacamole\Http\Abstract;

use Guacamole\Helpers\HeaderSupport\Enum\Header;
use Guacamole\Http\Response;

abstract class EndpointModel extends HttpResource {
    public function __construct() {
        self::addHeader(Header::ContentTypeApplicationJson);
    }

    /**
     * This method is called to get the response of the endpoint.
     * */
    abstract public static function response(EndpointModel $endpointModel): Response;
}