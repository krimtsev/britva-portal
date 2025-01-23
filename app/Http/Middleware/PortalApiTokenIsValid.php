<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortalApiTokenIsValid
{
    private function getRequestToken(Request $request) {
        $header = $request->header('Authorization', '');

        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }

        return null;
    }

    public function handle(Request $request, Closure $next) {
        $token = $this->getRequestToken($request);

        if (!empty($token)) {
            if($token === env('PORTAL_API_TOKEN', '')) {
                return $next($request);
            }

            return response()->json(['message' => 'token error'], 401);
        }

        return response()->json(['message' => 'token not found'], 401);
    }
}
