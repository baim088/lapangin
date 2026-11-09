<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if ($user->status === UserStatus::Suspended) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda sedang ditangguhkan. Silakan hubungi admin.',
            ]);
        }

        $userRoleValue = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;

        if (! empty($roles) && ! in_array($userRoleValue, $roles, true)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
