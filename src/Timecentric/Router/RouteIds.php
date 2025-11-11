<?php

declare(strict_types=1);

namespace Timecentric\Router;

enum RouteIds: string {
    case Home = '/';
    case DynamicRoute = '/dynamic-route/:variable';
    case Login = '/auth/login';
    case LoginCallback = '/auth/google/callback';
    case Dashboard = '/dashboard';
}