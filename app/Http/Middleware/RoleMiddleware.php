<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            throw new AccessDeniedHttpException('Необходимо авторизоваться');
        }

        $user = Auth::user();

        /** @phpstan-ignore-next-line */
        if ($user->isAdmin()) {
            return $next($request);
        }

        foreach ($roles as $role) {
            /** @phpstan-ignore-next-line */
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        throw new AccessDeniedHttpException('Доступ закрыт');
    }
}
