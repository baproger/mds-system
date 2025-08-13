<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contract;
use App\Models\Branch;
use App\Services\ContractStateService;

class WorkflowTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stateService = new ContractStateService();

        // Создаем тестовых пользователей разных ролей
        $manager = User::create([
            'name' => 'Тест Менеджер',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'branch_id' => 1,
        ]);

        $rop = User::create([
            'name' => 'Тест РОП',
            'email' => 'rop@test.com',
            'password' => bcrypt('password'),
            'role' => 'rop',
            'branch_id' => 1,
        ]);

        $accountant = User::create([
            'name' => 'Тест Бухгалтер',
            'email' => 'accountant@test.com',
            'password' => bcrypt('password'),
            'role' => 'accountant',
            'branch_id' => 1,
        ]);

        // Создаем тестовый договор
        $contract = Contract::create([
            'contract_number' => 'TEST-001',
            'user_id' => $manager->id,
            'branch_id' => 1,
            'client' => 'Тестовый Клиент',
            'instagram' => '@testclient',
            'iin' => '123456789012',
            'phone' => '+7 777 123 45 67',
            'phone2' => '+7 777 123 45 68',
            'address' => 'Тестовый адрес',
            'payment' => 'Наличный',
            'date' => now(),
            'category' => 'Comfort',
            'model' => 'Стандарт',
            'width' => 1000,
            'height' => 2000,
            'design' => 'Меняется',
            'leaf' => 'Одинарная',
            'framugawidth' => '100',
            'framugaheight' => '100',
            'forging' => 'Нет',
            'opening' => 'Правое',
            'frame' => 'Стандарт',
            'outer_panel' => 'Порошково-полимерное',
            'outer_cover' => 'Порошково-полимерное',
            'outer_cover_color' => 'Белый',
            'metal_cover_hidden' => 'Порошково-полимерное',
            'metal_cover_color' => 'Белый',
            'inner_trim' => 'Порошково-полимерное',
            'inner_cover' => 'Порошково-полимерное',
            'inner_trim_color' => 'Белый',
            'glass_unit' => 'Однокамерный',
            'extra' => 'Нет',
            'lock' => 'Стандарт',
            'handle' => 'Стандарт',
            'steel_thickness' => 1.5,
            'canvas_thickness' => 40,
            'measurement' => 'online',
            'delivery' => '+',
            'installation' => 'Тестовая установка',
            'order_total' => 150000,
            'order_deposit' => 50000,
            'order_remainder' => 100000,
            'order_due' => 100000,
            'status' => 'draft',
            'version' => 1,
            'manager' => 'Тест Менеджер',
        ]);

        // Тестируем workflow
        try {
            // 1. Менеджер отправляет на РОП
            $stateService->submitToRop($contract, $manager);
            $this->command->info('✅ Договор отправлен на РОП');

            // 2. РОП отправляет бухгалтеру
            $stateService->submitToAccountant($contract, $rop);
            $this->command->info('✅ Договор отправлен бухгалтеру');

            // 3. Бухгалтер возвращает на доработку
            $stateService->returnForRevision($contract, $accountant, 'Нужно исправить сумму договора');
            $this->command->info('✅ Договор возвращен на доработку');

            // 4. РОП снова отправляет бухгалтеру
            $stateService->submitToAccountant($contract, $rop);
            $this->command->info('✅ Договор снова отправлен бухгалтеру');

            // 5. Бухгалтер одобряет
            $stateService->approve($contract, $accountant, 'Договор одобрен');
            $this->command->info('✅ Договор одобрен');

        } catch (\Exception $e) {
            $this->command->error('❌ Ошибка в workflow: ' . $e->getMessage());
        }

        $this->command->info('🎉 Тестовые данные workflow созданы!');
        $this->command->info('Логин для тестирования:');
        $this->command->info('Менеджер: manager@test.com / password');
        $this->command->info('РОП: rop@test.com / password');
        $this->command->info('Бухгалтер: accountant@test.com / password');
    }
}
