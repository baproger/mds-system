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

        // Бухгалтер
        User::create([
            'name' => 'Айжан Бухгалтер',
            'email' => 'accountant@mdsdoors.kz',
            'password' => Hash::make('password'),
            'role' => 'accountant',
            'branch_id' => $branch ? $branch->id : null,
        ]);



        // Роль директора удалена

        $this->command->info('Новые роли пользователей созданы!');
    }
}
