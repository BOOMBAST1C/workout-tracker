<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // 1. Проверяем авторизацию
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Сначала войдите в аккаунт.');
        }

        // 2. Получаем роль пользователя
        $userRole = auth()->user()->role->name ?? '';

        // 3. Разбиваем строку ролей по символу '|' (например, 'admin|trainer')
        $allowedRoles = explode('|', $roles);

        // 4. Проверяем доступ
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'У вас нет прав доступа к этой странице.');
        }

        return $next($request);
    }
}