<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Middleware\HandleCors;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ... Middleware route của bạn ...
        // CorsMiddleware::class,
       
    ];

    /**
     * The application's global middleware.
     *
     * @var array
     */
    protected $middleware = [
        // Các middleware khác...
        \App\Http\Middleware\CorsMiddleware::class,
    ];

    /**
     * The application's route groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            
        ],
    
        'api' => [
            \App\Http\Middleware\CorsMiddleware::class,
          
        ],
    ];

    /**
     * Boot the application's HTTP kernel.
     *
     * @return void
     */
}