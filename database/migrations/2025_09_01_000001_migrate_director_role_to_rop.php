<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Переводим всех пользователей с ролью 'director' в 'rop'
        DB::table('users')->where('role', 'director')->update(['role' => 'rop']);
    }

    public function down(): void
    {
        // Обратный откат невозможен однозначно; оставляем как есть
    }
};


