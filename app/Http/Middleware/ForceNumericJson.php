<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ForceNumericJson
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            $status = $response->getStatusCode();
            $headers = $response->headers->all();

            return response()->json($data, $status, $headers, JSON_NUMERIC_CHECK);
        }

        return $response;
    }
}
