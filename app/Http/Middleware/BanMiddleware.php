<?php

namespace App\Http\Middleware;

use App\Models\Ban;
use Carbon\Carbon;
use Closure;

class BanMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user) {
            $now = Carbon::now();

            $ban = Ban::where('user_id', $user->id)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)->first();

            if ($ban) {
                return response()->json(
                    ['error' => 'Ваша учетная запись заблокирована. Причина: ' . $ban->reason .
                        ". Блокировка действует до " . $ban->end_date],
                    403);
            }
        }

        return $next($request);
    }
}
