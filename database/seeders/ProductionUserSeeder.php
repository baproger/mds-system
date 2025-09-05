<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class ProductionUserSeeder extends Seeder
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

        // Создаем пользователя производства
        User::create([
            'name' => 'Производство',
            'email' => 'production@mdsdoors.kz',
            'password' => Hash::make('production123'),
            'role' => 'production',
            'branch_id' => $branch->id,
            'phone' => '+7 (777) 123-45-67',
            'is_active' => true,
        ]);

        $this->command->info('Пользователь производства создан: production@mdsdoors.kz / production123');
    }
}