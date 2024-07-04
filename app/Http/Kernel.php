<?php

namespace App\Http;

use App\Http\Middleware\CorsMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ... Middleware route của bạn ...
        CorsMiddleware::class,
    ];

    /**
     * The application's global middleware.
     *
     * @var array
     */
    protected $middleware = [
        // ... Middleware toàn cầu của bạn ...
    ];

    /**
     * The application's route groups.
     *
     * @var array
     */
    protected $routeGroups = [
        // ... Nhóm route của bạn ...
    ];

    /**
     * Boot the application's HTTP kernel.
     *
     * @return void
     */
}