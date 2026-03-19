<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-Key');

        if (!$key) {
            return response()->json(['error' => 'Missing API key.'], 401);
        }

        $apiKey = ApiKey::where('key', $key)->where('is_active', true)->first();

        if (!$apiKey) {
            return response()->json(['error' => 'Invalid or inactive API key.'], 401);
        }

        // Check allowed domain against Origin or Referer header
        if ($apiKey->allowed_domain) {
            $origin  = $request->header('Origin', '');
            $referer = $request->header('Referer', '');
            $allowed = $apiKey->allowed_domain;

            $originMatch  = $origin  && str_contains($origin,  $allowed);
            $refererMatch = $referer && str_contains($referer, $allowed);

            if (!$originMatch && !$refererMatch) {
                return response()->json(['error' => 'Domain not allowed.'], 403);
            }
        }

        // Update last used timestamp without triggering model events
        $apiKey->updateQuietly(['last_used_at' => now()]);

        return $next($request);
    }
}
