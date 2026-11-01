<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Вы успешно вошли!');
        }

        return back()->withErrors(['login' => 'Неверный логин или пароль.'])->onlyInput('login');
    }

    public function showRegisterForm() { return view('auth.register'); }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:50', 'unique:users,login'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Находим роль "user"
        $userRole = Role::where('name', 'user')->first();

        $user = User::create([
            'login' => $validated['login'],
            'password' => $validated['password'],
            'role_id' => $userRole->id, // Автоматически назначаем роль "Пользователь"
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Регистрация прошла успешно!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Вы вышли из аккаунта.');
    }
}