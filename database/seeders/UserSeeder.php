<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем первый филиал для привязки
        $branch = Branch::first();
        if (!$branch) return;

        // Администратор
        User::updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('admin123'),
            'branch_id' => $branch->id,
            'role' => 'admin',
        ]);


    }
}
