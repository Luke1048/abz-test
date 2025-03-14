<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Models\Token;

class CheckUserRegistrationToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = Token::first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'The token is missing.',
            ], 401);
        } else if ($token && $token->updated_at->diffInMinutes(Carbon::now()) > 40) {
            return response()->json([
                'success' => false,
                'message' => 'The token expired.',
            ], 401);
        } else if ($token->is_used === 1) {
            return response()->json([
                'success' => false,
                'message' => 'The token is already used.',
            ], 401);
        } else if (substr($request->header('Authorization'), 7) !== $token->token) {
            return response()->json([
                'success' => false,
                'message' => 'The token mismatch.',
            ], 401);
        }

        return $next($request);
    }
}
