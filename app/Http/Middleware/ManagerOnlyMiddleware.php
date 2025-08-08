<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerOnlyMiddleware
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
        
        // Разрешаем доступ только для admin и manager
        if (!in_array($user->role, ['admin', 'manager'])) {
            \Log::info('ManagerOnlyMiddleware: Access denied', [
                'user_role' => $user->role,
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            abort(403, 'Доступ запрещен. Требуются права менеджера или администратора.');
        }
        
        \Log::info('ManagerOnlyMiddleware: Access granted', [
            'user_role' => $user->role,
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return $next($request);
    }
}
