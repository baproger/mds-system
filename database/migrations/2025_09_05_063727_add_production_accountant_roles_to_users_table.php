<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Обновляем комментарий к колонке role, добавляя новые роли
            $table->string('role')->comment('admin, manager, rop, production, accountant')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Возвращаем старый комментарий
            $table->string('role')->comment('admin, manager, rop')->change();
        });
    }
};
