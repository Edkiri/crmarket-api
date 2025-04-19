<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $marketId = $request->header('market_id');

        if (!$marketId) {
            return response()->json(['message' => 'market_id header is required'], Response::HTTP_BAD_REQUEST);
        }

        if (!$request->user()->markets()->where('id', $marketId)->exists()) {
            return response()->json(['message' => 'You are not the owner of this market.'], Response::HTTP_FORBIDDEN);
        }

        $request->merge(['market_id' => $marketId]);

        return $next($request);
    }
}
