<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class NewRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branch::first();

        // Создаем РОП
        User::create([
            'name' => 'РОП Менеджер',
            'email' => 'rop@mdsdoors.kz',
            'password' => Hash::make('password'),
            'role' => 'rop',
            'branch_id' => 1
        ]);


        // Роль директора удалена

        $this->command->info('Новые роли пользователей созданы!');
    }
}
