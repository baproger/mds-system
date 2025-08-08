<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем админского пользователя
        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Администратор',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => null, // Админ не привязан к филиалу
        ]);

        // Создаем тестового менеджера
        User::firstOrCreate(['email' => 'manager@example.com'], [
            'name' => 'Менеджер Шымкент',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'branch_id' => 1, // Шымкент Прайм парк
        ]);
    }
}
