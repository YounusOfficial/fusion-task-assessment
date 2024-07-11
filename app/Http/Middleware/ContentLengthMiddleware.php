<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentLengthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $maxContentLength = 1024 * 1024; // 1MB

        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
            if ($request->headers->has('Content-Length') && $request->header('Content-Length') > $maxContentLength) {
                return response()->json(['error' => 'Request body too large'], Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
            }
        }

        return $next($request);
    }
}
