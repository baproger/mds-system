<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

        // Разрешаем доступ admin, rop и manager ролям
        if (!in_array(Auth::user()->role, ['admin', 'rop', 'manager'])) {
            abort(403, 'Доступ запрещен. Требуются права администратора, РОП или менеджера.');
        }

        return $next($request);
    }
}
