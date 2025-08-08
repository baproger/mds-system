<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // Загружаем связанные данные в зависимости от роли
        if ($user->role === 'admin') {
            // Админ видит все свои данные
            $user->load(['contracts', 'branch']);
        } elseif ($user->role === 'manager') {
            // Менеджер видит только свои данные
            $user->load(['contracts' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }, 'branch']);
        } elseif ($user->role === 'rop') {
            // РОП видит договоры своего филиала в профиле
            $user->load(['contracts' => function($query) use ($user) {
                $query->whereHas('user', function($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                });
            }, 'branch']);
        }
        
        return view('profile.show', compact('user'));
    }

    public function edit($id = null)
    {
        // Админ может редактировать любого пользователя, остальные только себя
        if (Auth::user()->role === 'admin' && $id) {
            $user = User::findOrFail($id);
        } else {
            $user = Auth::user();
        }
        
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id = null)
    {
        // Админ может обновлять любого пользователя, остальные только себя
        if (Auth::user()->role === 'admin' && $id) {
            $user = User::findOrFail($id);
        } else {
            $user = Auth::user();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'Имя обязательно для заполнения',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email',
            'email.unique' => 'Этот email уже используется',
            'phone.max' => 'Телефон не должен превышать 20 символов',
            'current_password.required_with' => 'Введите текущий пароль для изменения',
            'new_password.min' => 'Новый пароль должен содержать минимум 8 символов',
            'new_password.confirmed' => 'Пароли не совпадают',
        ]);

        // Обновляем основные данные
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Обновляем пароль если указан
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Неверный текущий пароль'
                    ]);
                }
                return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Профиль успешно обновлен'
            ]);
        }

        return redirect()->route('profile.show')->with('success', 'Профиль успешно обновлен');
    }
} 