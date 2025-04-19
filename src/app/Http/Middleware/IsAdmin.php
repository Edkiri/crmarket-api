<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $marketId = $request->get('market_id');

        if (!$marketId) {
            return response()->json([
                'message' => 'market_id is required'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $request->user();

        $isAdmin = DB::table('market_user_role')
            ->where('user_id', $user->id)
            ->where('market_id', $marketId)
            ->whereIn('role_id', [1, 2])
            ->first();

        if (!$isAdmin) {
            return response()->json([
                'message' => 'Not allowed'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
