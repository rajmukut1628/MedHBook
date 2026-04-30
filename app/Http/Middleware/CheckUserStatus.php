<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            $user = auth()->user();

            if ($user->suspended_until && now()->lt($user->suspended_until)) {
                auth()->logout();

                return redirect()->route('login')
                    ->with('error', 'Your account is suspended until ' . $user->suspended_until->format('d M Y, h:i A') . '. Reason: ' . ($user->suspend_reason ?? 'Not provided'));
            }

            if ($user->suspended_until && now()->gte($user->suspended_until)) {
                $user->update([
                    'status' => 'active',
                    'suspended_until' => null,
                    'suspend_reason' => null,
                ]);
            }

            if ($user->status === 'blocked' || $user->status === 'inactive' || $user->status === 'suspended') {
                auth()->logout();

                return redirect()->route('login')
                    ->with('error', 'Your account is inactive. Contact administrator.');
            }
        }

        return $next($request);
    }
}