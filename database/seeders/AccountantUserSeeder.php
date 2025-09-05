<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class AccountantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем первый филиал для привязки пользователя
        $branch = Branch::first();
        
        if (!$branch) {
            $this->command->error('Нет филиалов в базе данных. Сначала создайте филиал.');
            return;
        }

        // Создаем пользователя бухгалтера
        User::create([
            'name' => 'Бухгалтер',
            'email' => 'accountant@mdsdoors.kz',
            'password' => Hash::make('accountant123'),
            'role' => 'accountant',
            'branch_id' => $branch->id,
            'phone' => '+7 (777) 123-45-68',
            'is_active' => true,
        ]);

        $this->command->info('Пользователь бухгалтера создан: accountant@mdsdoors.kz / accountant123');
    }
}