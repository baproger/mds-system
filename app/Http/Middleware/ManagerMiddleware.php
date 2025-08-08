<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
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
        
        // Разрешаем доступ для admin, manager и rop
        if (!in_array($user->role, ['admin', 'manager', 'rop'])) {
            abort(403, 'Доступ запрещен. Требуются права менеджера/РОП или администратора.');
        }

        return $next($request);
    }
} 