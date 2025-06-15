<?php

declare(strict_types=1);

namespace Guacamole\Helpers\HeaderSupport\Enum;

enum Header: string {
    case ContentTypeApplicationJson = 'Content-Type: application/json';
    case ContentTypeTextHtml = 'Content-Type: text/html';
    case ContentTypeTextPlain = 'Content-Type: text/plain';
    case ContentTypeApplicationXml = 'Content-Type: application/xml';
    case ContentTypeApplicationFormUrlEncoded = 'application/x-www-Content-Type: form-urlencoded';
    case ContentTypeMultipartFormData = 'multipart/Content-Type: form-data';
}
