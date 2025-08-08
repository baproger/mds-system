<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            NewRolesSeeder::class, // Новые роли пользователей
            // ManagerSeeder::class, // Отключено - менеджеры добавляются вручную
        ]);
    }
}
