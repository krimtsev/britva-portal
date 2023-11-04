<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateUserActivity
{
    /**
     * Обновление последней даты активности пользователя
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            $user_last_activity = "last_activity_" . Auth::user()->id;

            if(!\Cache::has($user_last_activity)) {

                $user = Auth::user();

                $user->last_activity = \Date::now();

                $user->update();

                $cachingTime = now()->addMinutes(10);
                \Cache::put($user_last_activity, true, $cachingTime);
            }
        }

        return $next($request);
    }
}
