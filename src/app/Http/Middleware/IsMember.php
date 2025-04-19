<?php

namespace App\Http\Middleware;

use App\Models\Market;
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
                'message' => 'market_id query is required'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $request->user();

        $isMember = DB::table('market_user_role')
            ->where('user_id', $user->id)
            ->where('market_id', $marketId)
            ->first();

        if (!$isMember) {
            return response()->json([
                'message' => 'You are not a member of this market'
            ], Response::HTTP_FORBIDDEN);
        }

        $request->merge(['market_id' => $marketId]);

        return $next($request);
    }
}
