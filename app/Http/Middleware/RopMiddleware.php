<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RopMiddleware
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
        
        // Проверяем, является ли пользователь админом или РОП
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Проверяем, является ли пользователь РОП
        if ($user->role === 'rop' && $user->branch) {
            // Добавляем информацию о филиале РОП в request
            $request->attributes->add(['rop_branch_id' => $user->branch_id]);
            return $next($request);
        }

        abort(403, 'Доступ запрещен. Требуются права РОП.');
    }
}
