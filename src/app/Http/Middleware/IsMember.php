<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsMember
{
    public function handle(Request $request, Closure $next): Response
    {
        $marketId = $request->header('market-id');

        if (!$marketId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_FORBIDDEN);
        }

        $user = $request->user();

        $isMember = DB::table('market_user_role')
            ->where('user_id', $user->id)
            ->where('market_id', $marketId)
            ->first();

        if (!$isMember) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_FORBIDDEN);
        }

        $request->merge(['market_id' => $marketId]);

        return $next($request);
    }
}
