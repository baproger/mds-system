<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Удаляем старые филиалы
        DB::table('branches')->truncate();

        // Добавляем новые филиалы с правильными диапазонами
        $branches = [
            ["name" => "Шымкент Прайм парк", "code" => "SHY-PP", "contract_counter" => 20000, "created_at" => now(), "updated_at" => now()],
            ["name" => "Шымкент Ремзона", "code" => "SHY-RZ", "contract_counter" => 30000, "created_at" => now(), "updated_at" => now()],
            ["name" => "Актобе", "code" => "AKT", "contract_counter" => 40000, "created_at" => now(), "updated_at" => now()],
            ["name" => "Алматы Тастак", "code" => "ALA-TST", "contract_counter" => 50000, "created_at" => now(), "updated_at" => now()],
            ["name" => "Алматы СтройСити", "code" => "ALA-SC", "contract_counter" => 58000, "created_at" => now(), "updated_at" => now()],
            ["name" => "Тараз", "code" => "TRZ", "contract_counter" => 100000, "created_at" => now(), "updated_at" => now()],
            ["name" => "Атырау", "code" => "ATR", "contract_counter" => 120000, "created_at" => now(), "updated_at" => now()],
            ["name" => "Ташкент", "code" => "TAS", "contract_counter" => 60000, "created_at" => now(), "updated_at" => now()],
        ];

        DB::table('branches')->insert($branches);
    }

    public function down(): void
    {
        // Восстанавливаем старые филиалы
        DB::table('branches')->truncate();

        $branches = [
            ["name" => "Шымкент", "code" => "SHY", "contract_counter" => 1, "created_at" => now(), "updated_at" => now()],
            ["name" => "Алматы", "code" => "ALA", "contract_counter" => 1, "created_at" => now(), "updated_at" => now()],
            ["name" => "Астана", "code" => "AST", "contract_counter" => 1, "created_at" => now(), "updated_at" => now()],
        ];

        DB::table('branches')->insert($branches);
    }
};
