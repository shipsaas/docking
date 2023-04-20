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
        $accessKey = config('docking.public-access-key');
        if (!$accessKey || $accessKey === $request->header(static::X_ACCESS_KEY_HEADER)) {
            return $next($request);
        }

        return new JsonResponse([
            'error' => 'Invalid Key'
        ], 401);
    }
}
