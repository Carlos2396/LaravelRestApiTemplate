<?php

namespace Tests;

class Helper
{
    static $middlewares = [
        \App\Http\Middleware\Authenticate::class,
        \App\Http\Middleware\RoleMiddleware::class
    ];
}
