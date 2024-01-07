<?php

namespace App\Http\Middleware;

use App\Models\ApiCall;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiCall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        ApiCall::create([
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $response->status(),
        ]);

        return $response;
    }
}
