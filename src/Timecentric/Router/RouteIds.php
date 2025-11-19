<?php

declare(strict_types=1);

namespace Timecentric\Router;

enum RouteIds: string {
    // General
    case Home = '/';
    case DynamicRoute = '/dynamic-route/:variable';

    // Auth
    case Login = '/auth/login';
    case LoginCallback = '/auth/google/callback';

    // App
    case Dashboard = '/dashboard';

    // Errors
    case NotFound = '/not-found';
    case BadRequest = '/bad-request';
    case ServerError = '/server-error';
}