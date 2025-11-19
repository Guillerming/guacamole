<?php

declare(strict_types=1);

namespace Timecentric\Enums;

enum CookieNames: string {
    /** Session */
    case Token = 'token';
    case GoogleOAuthState = 'google-oauth-state';
}