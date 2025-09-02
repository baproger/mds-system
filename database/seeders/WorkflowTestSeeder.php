<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contract;
use App\Models\Branch;
use App\Services\ContractStateService;
use Illuminate\Support\Facades\Hash;

class WorkflowTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stateService = new ContractStateService();

        // Создаем тестовых пользователей разных ролей
        $branch = Branch::create([
            'name' => 'Тестовый филиал',
            'address' => 'Тестовый адрес филиала',
            'phone' => '+7 777 123 45 67',
            'email' => 'branch@test.com',
        ]);

        // Создаем РОП для тестирования
        $rop = User::create([
            'name' => 'РОП Тест',
            'email' => 'rop@test.com',
            'password' => Hash::make('password'),
            'role' => 'rop',
            'branch_id' => $branch->id
        ]);

        // Создаем менеджера для тестирования
        $manager = User::create([
            'name' => 'Менеджер Тест',
            'email' => 'manager@test.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'branch_id' => $branch->id
        ]);

        // Создаем договор для тестирования
        $contract = Contract::create([
            'contract_number' => 'TEST-001',
            'user_id' => $manager->id,
            'branch_id' => $branch->id,
            'status' => Contract::STATUS_DRAFT,
            'order_total' => 100000,
            'order_deposit' => 30000,
            'order_remainder' => 70000,
            'order_due' => now()->addDays(30),
            'payment' => 'cash',
            'model' => 'Англия',
            'outer_cover' => 'Дуб',
            'inner_trim' => 'Дуб',
            'inner_cover' => 'Дуб',
            'glass_unit' => 'Однокамерный',
            'lock' => 'Стандарт',
            'canvas_height' => 2100,
            'canvas_width' => 900,
            'opening_side' => 'left',
            'installation_type' => 'new',
            'installation_date' => now()->addDays(7),
            'notes' => 'Тестовый договор для проверки workflow'
        ]);

        // Тестируем workflow
        $this->command->info('Тестируем workflow...');

        // Менеджер отправляет договор на проверку РОП
        $stateService->submitToRop($contract, $manager);
        $this->command->info('Договор отправлен на проверку РОП');

        // РОП возвращает договор на доработку
        $stateService->returnForRevision($contract, $rop, 'Нужно исправить сумму договора');
        $this->command->info('Договор возвращен на доработку');

        // Менеджер снова отправляет договор на проверку РОП
        $stateService->submitToRop($contract, $manager);
        $this->command->info('Договор снова отправлен на проверку РОП');

        // РОП одобряет договор
        $stateService->approve($contract, $rop, 'Договор одобрен');
        $this->command->info('Договор одобрен РОП');

        // РОП запускает производство
        $stateService->startProduction($contract, $rop);
        $this->command->info('Производство запущено');

        // РОП проверяет качество
        $stateService->qualityCheck($contract, $rop);
        $this->command->info('Качество проверено');

        // РОП отмечает готовность
        $stateService->markReady($contract, $rop);
        $this->command->info('Договор готов к отгрузке');

        // РОП отгружает
        $stateService->ship($contract, $rop);
        $this->command->info('Договор отгружен');

        // РОП завершает
        $stateService->complete($contract, $rop);
        $this->command->info('Договор завершен');

        $this->command->info('Workflow тестирование завершено!');
        $this->command->info('');
        $this->command->info('Тестовые пользователи:');
        $this->command->info('Админ: admin@test.com / password');
        $this->command->info('РОП: rop@test.com / password');
        $this->command->info('Менеджер: manager@test.com / password');
    }
}
