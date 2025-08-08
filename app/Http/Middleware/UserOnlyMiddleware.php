<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Разрешаем доступ только для обычных пользователей (не admin/manager/rop)
        if (in_array($user->role, ['admin', 'manager', 'rop'])) {
            // Перенаправляем на соответствующий дашборд
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif ($user->role === 'rop') {
                return redirect()->route('rop.dashboard');
            }
        }

        return $next($request);
    }
}
