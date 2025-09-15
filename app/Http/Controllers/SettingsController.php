<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('settings.index', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        
        return back()->with('success', 'Профиль успешно обновлен');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return back()->with('success', 'Пароль успешно изменен');
    }
    
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'theme' => 'nullable|in:light,dark',
            'language' => 'nullable|in:ru,en',
            'notifications_email' => 'nullable|boolean',
            'notifications_sms' => 'nullable|boolean',
        ]);
        
        // Здесь можно сохранить настройки в отдельную таблицу или в JSON поле
        $preferences = [
            'theme' => $request->theme ?? 'light',
            'language' => $request->language ?? 'ru',
            'notifications_email' => $request->has('notifications_email'),
            'notifications_sms' => $request->has('notifications_sms'),
        ];
        
        // Сохраняем в сессию для демонстрации
        session(['user_preferences' => $preferences]);
        
        return back()->with('success', 'Настройки успешно обновлены');
    }
    
    public function systemSettings(Request $request)
    {
        // Только для админа
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Доступ запрещен. Требуются права администратора для изменения настроек.');
        }
        
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string|max:20',
            'backup_enabled' => 'nullable|boolean',
            'auto_logout_minutes' => 'nullable|integer|min:5|max:480',
        ]);
        
        // Здесь можно сохранить системные настройки
        $systemSettings = [
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'backup_enabled' => $request->has('backup_enabled'),
            'auto_logout_minutes' => $request->auto_logout_minutes ?? 30,
        ];
        
        // Сохраняем в сессию для демонстрации
        session(['system_settings' => $systemSettings]);
        
        return back()->with('success', 'Системные настройки обновлены');
    }
}
