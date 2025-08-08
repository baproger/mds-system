<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ["name" => "Шымкент Прайм парк", "code" => "SHY-PP", "contract_counter" => 20000],
            ["name" => "Шымкент Ремзона", "code" => "SHY-RZ", "contract_counter" => 30000],
            ["name" => "Актобе", "code" => "AKT", "contract_counter" => 40000],
            ["name" => "Алматы Тастак", "code" => "ALA-TST", "contract_counter" => 50000],
            ["name" => "Алматы СтройСити", "code" => "ALA-SC", "contract_counter" => 58000],
            ["name" => "Тараз", "code" => "TRZ", "contract_counter" => 100000],
            ["name" => "Атырау", "code" => "ATR", "contract_counter" => 120000],
            ["name" => "Ташкент", "code" => "TAS", "contract_counter" => 60000],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(['code' => $branch['code']], $branch);
        }
    }
}
