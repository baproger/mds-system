<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Перенаправляем пользователей на соответствующие дашборды
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif (Auth::user()->role === 'manager') {
                return redirect()->intended(route('manager.dashboard'));
            } elseif (Auth::user()->role === 'rop') {
                return redirect()->intended(route('rop.dashboard'));
            }
            
            return redirect()->intended(route('contracts.index'));
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegister()
    {
        $branches = Branch::all();
        return view('auth.register', compact('branches'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'branch_id' => $validated['branch_id'],
            'role' => 'manager', // роль по умолчанию
        ]);

        Auth::login($user);

        // Перенаправляем на соответствующий дашборд
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'manager') {
            return redirect()->route('manager.dashboard');
        } elseif ($user->role === 'rop') {
            return redirect()->route('rop.dashboard');
        }
        
        return redirect()->route('contracts.index');
    }
}
