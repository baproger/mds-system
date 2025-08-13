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
        Schema::create('contract_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role'); // роль пользователя на момент изменения
            $table->string('field'); // название измененного поля
            $table->text('old_value')->nullable(); // старое значение
            $table->text('new_value')->nullable(); // новое значение
            $table->integer('version_from'); // версия до изменения
            $table->integer('version_to'); // версия после изменения
            $table->timestamp('changed_at');
            $table->timestamps();
            
            // Индексы для производительности
            $table->index(['contract_id', 'changed_at']);
            $table->index(['user_id', 'changed_at']);
            $table->index('field');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_changes');
    }
};
