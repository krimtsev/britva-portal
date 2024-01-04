<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next) {
        $token = $request->query("api_token");

        if (!empty($token)) {
            if($token === env('EXTERNAL_API_TOKEN', '')) {
                return $next($request);
            }

            return response()->json(['message' => 'token error'], 401);
        }

        return response()->json(['message' => 'token not found'], 401);
    }
}
