<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite не поддерживает ALTER TABLE для enum, поэтому пересоздаем таблицу
        Schema::dropIfExists('approvals');
        
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->string('from_role'); // роль отправителя
            $table->string('to_role'); // роль получателя
            $table->enum('action', [
                'submit', 
                'approve', 
                'reject', 
                'hold', 
                'return',
                'start_production',
                'quality_check',
                'mark_ready',
                'ship',
                'complete'
            ]); // действие
            $table->text('comment')->nullable(); // комментарий
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // кто создал
            $table->timestamps();
            
            // Индексы для производительности
            $table->index(['contract_id', 'created_at']);
            $table->index(['from_role', 'to_role']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
        
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->string('from_role'); // роль отправителя
            $table->string('to_role'); // роль получателя
            $table->enum('action', [
                'submit', 
                'approve', 
                'reject', 
                'hold', 
                'return'
            ]); // действие
            $table->text('comment')->nullable(); // комментарий
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // кто создал
            $table->timestamps();
            
            // Индексы для производительности
            $table->index(['contract_id', 'created_at']);
            $table->index(['from_role', 'to_role']);
            $table->index('action');
        });
    }
};
