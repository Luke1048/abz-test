<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Token;
use Carbon\Carbon;

class TokenController extends Controller
{
    public function __invoke() {
        $token = Token::first();

        if (!$token) {
            $tokenValue = Crypt::encrypt(random_bytes(40));

            Token::create([
                'token' => $tokenValue,
                'is_used' => 0,
            ]);

            return response()->json([
                'success' => true,
                'token' => $tokenValue,
            ]);
        } else if ($token->is_used === 1 || $token->updated_at->diffInMinutes(Carbon::now()) > 40) {
            $tokenValue = Crypt::encrypt(random_bytes(40));

            $token->token = $tokenValue;
            $token->is_used = 0;
            $token->save();

            return response()->json([
                'success' => true,
                'token' => $tokenValue,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'token' => $token->token,
            ]);
        }
    }
}
