<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, $next)
    {
        if ($request->isMethod('OPTIONS')) {
            return $this->handleOptionsRequest($request);
        }

        return $next($request);
    }

    /**
     * Handle an OPTIONS request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function handleOptionsRequest(Request $request)
    {
        $allowedHeaders = [
            'Content-Type',
            'Accept',
            'Authorization',
            'X-Requested-With',
        ];

        $allowedMethods = [
            'GET',
            'HEAD',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
        ];

        $response = response()->json();

        $response->headers->set('Access-Control-Allow-Origin', '*'); // Cho phép truy cập từ tất cả các miền
        $response->headers->set('Access-Control-Allow-Methods', implode(', ', $allowedMethods));
        $response->headers->set('Access-Control-Allow-Headers', implode(', ', $allowedHeaders));

        return $response;
    }
}