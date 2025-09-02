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
        // Удаляем пользователей с ролью accountant
        DB::table('users')->where('role', 'accountant')->delete();
        
        // Обновляем enum ролей
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['manager', 'admin', 'rop'])->default('manager')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['manager', 'admin', 'accountant'])->default('manager')->after('password');
        });
    }
};
