<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ValidatePublicAccessKey
{
    public const X_ACCESS_KEY_HEADER = 'X-Access-Key';

    public function handle(Request $request, Closure $next): Response
    {
        $headerValue = $request->header(static::X_ACCESS_KEY_HEADER);

        // 1. Validate public access key (API Request between services)
        $accessKey = config('docking.public-access-key');
        if (!$accessKey || $accessKey === $headerValue) {
            return $next($request);
        }

        // 2. Validate console password
        $consoleEnabled = config('docking.console-enabled');
        $consolePassword = config('docking.console-password');
        if ($consoleEnabled && $consolePassword === $headerValue) {
            return $next($request);
        }

        return new JsonResponse([
            'error' => 'Invalid Key'
        ], 401);
    }
}
