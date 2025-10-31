<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CrmController extends Controller
{
    /**
     * Показать демо-страницу CRM
     */
    public function demo()
    {
        return view('crm.demo');
    }

    /**
     * Показать канбан-доску
     */
    public function kanban(Request $request)
    {
        $user = Auth::user();
        $branchId = null;
        $userId = null;

        // Определяем фильтры в зависимости от роли
        if ($user->role === 'manager') {
            $userId = $user->id;
        } elseif ($user->role === 'rop') {
            $branchId = $user->branch_id;
        } elseif ($user->role === 'production') {
            // Production видит только договоры в производстве
            $contractsByStatus = Contract::forRole($user)->get()->groupBy('status');
            // Production видит только статусы, связанные с производством
            $statuses = [
                Contract::STATUS_IN_PRODUCTION,
                Contract::STATUS_QUALITY_CHECK,
                Contract::STATUS_READY,
                Contract::STATUS_SHIPPED
            ];
            return view('crm.kanban', compact('contractsByStatus', 'statuses'));
        }

        // Получаем договоры для канбан-доски
        $contractsByStatus = Contract::getKanbanContracts($branchId, $userId);
        
        // Получаем все статусы для отображения колонок
        $statuses = Contract::FUNNEL_ORDER;

        return view('crm.kanban', compact('contractsByStatus', 'statuses'));
    }

    /**
     * Получить данные для канбан-доски (AJAX)
     */
    public function kanbanData(Request $request)
    {
        $user = Auth::user();
        $branchId = null;
        $userId = null;

        // Определяем фильтры в зависимости от роли
        if ($user->role === 'manager') {
            $userId = $user->id;
        } elseif ($user->role === 'rop') {
            $branchId = $user->branch_id;
        } elseif ($user->role === 'production') {
            // Production видит только договоры в производстве
            $contracts = Contract::forRole($user)->with(['user', 'branch'])->get();
            // Production видит только статусы, связанные с производством
            $productionStatuses = [
                Contract::STATUS_IN_PRODUCTION,
                Contract::STATUS_QUALITY_CHECK,
                Contract::STATUS_READY,
                Contract::STATUS_SHIPPED
            ];
            return response()->json([
                'contracts' => $contracts->groupBy('status'),
                'statuses' => $productionStatuses
            ]);
        }

        // Получаем договоры для канбан-доски
        $contractsByStatus = Contract::getKanbanContracts($branchId, $userId);
        
        return response()->json([
            'contracts' => $contractsByStatus,
            'statuses' => Contract::FUNNEL_ORDER
        ]);
    }

    /**
     * Показать дашборд CRM
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $branchId = $request->get('branch_id');
        $period = $request->get('period', 'all');

        // Определяем фильтры в зависимости от роли
        if ($user->role === 'rop') {
            $branchId = $user->branch_id;
        }

        // Формируем базовый запрос и выгружаем договоры за период/филиал
        $contractsQuery = Contract::query();
        if ($branchId) {
            $contractsQuery->where('branch_id', $branchId);
        }
        if ($period !== 'all') {
            $contractsQuery->whereBetween('created_at', [now()->startOf($period), now()->endOf($period)]);
        }
        $contractsForStats = $contractsQuery->get(['status', 'user_id', 'branch_id', 'order_total', 'created_at', 'updated_at']);

        // Считаем распределение по статусам на основании выгруженных договоров
        $statusStats = [];
        foreach (Contract::FUNNEL_ORDER as $status) {
            $statusStats[$status] = 0;
        }
        foreach ($contractsForStats as $contract) {
            if (isset($statusStats[$contract->status])) {
                $statusStats[$contract->status]++;
            }
        }

        // Получаем данные для графиков
        $monthlyData = $this->getMonthlyData($branchId, $period);
        $topManagers = $this->getTopManagers($branchId, $period);
        $recentContracts = $this->getRecentContracts($branchId);

        // Получаем KPI
        $kpis = $this->calculateKPIs($branchId, $period);

        // Получаем филиалы для фильтра
        $branches = Branch::all();

        return view('crm.dashboard', compact(
            'statusStats', 
            'monthlyData', 
            'topManagers', 
            'recentContracts', 
            'kpis', 
            'branches', 
            'branchId', 
            'period'
        ));
    }

    /**
     * Обновить статус договора через drag & drop
     */
    public function updateStatus(Request $request, Contract $contract)
    {
        $user = Auth::user();
        $newStatus = $request->input('status');

        // Валидация входных данных
        if (empty($newStatus) || is_null($newStatus)) {
            \Log::error('Попытка обновления статуса с пустым значением', [
                'contract_id' => $contract->id,
                'new_status' => $newStatus,
                'user_id' => $user->id
            ]);
            return response()->json(['error' => 'Статус не может быть пустым'], 400);
        }

        // Проверяем, что новый статус валиден
        $validStatuses = [
            Contract::STATUS_DRAFT,
            Contract::STATUS_PENDING_ROP,
            Contract::STATUS_APPROVED,
            Contract::STATUS_REJECTED,
            Contract::STATUS_ON_HOLD,
            Contract::STATUS_IN_PRODUCTION,
            Contract::STATUS_QUALITY_CHECK,
            Contract::STATUS_READY,
            Contract::STATUS_SHIPPED,
            Contract::STATUS_COMPLETED,
            Contract::STATUS_RETURNED
        ];

        if (!in_array($newStatus, $validStatuses)) {
            \Log::error('Попытка обновления статуса с невалидным значением', [
                'contract_id' => $contract->id,
                'new_status' => $newStatus,
                'user_id' => $user->id
            ]);
            return response()->json(['error' => 'Невалидный статус'], 400);
        }

        // Специальное правило для менеджера: только DRAFT -> PENDING_ROP
        if ($user->role === 'manager') {
            if (!($contract->status === Contract::STATUS_DRAFT && $newStatus === Contract::STATUS_PENDING_ROP)) {
                \Log::warning('Менеджер попытался изменить недопустимый статус', [
                    'contract_id' => $contract->id,
                    'from' => $contract->status,
                    'to' => $newStatus,
                    'user_id' => $user->id,
                ]);
                return response()->json(['error' => 'Менеджерам доступно только отправить на рассмотрение'], 403);
            }
        }

        // Логируем для отладки
        \Log::info('Обновление статуса договора', [
            'contract_id' => $contract->id,
            'old_status' => $contract->status,
            'new_status' => $newStatus,
            'user_id' => $user->id,
            'user_role' => $user->role
        ]);

        // Проверяем права на изменение статуса
        $action = $this->getActionForStatus($newStatus);
        
        // Если действие не найдено или пользователь не имеет прав, проверяем права админа, РОП, менеджера или production
        if (!$contract->canPerformAction($action, $user)) {
            if ($user->role === 'admin' && $contract->canPerformAction('admin_change_status', $user)) {
                $action = 'admin_change_status';
            } elseif (in_array($user->role, ['rop', 'manager']) && $this->canRopChangeStatus($contract, $user, $newStatus)) {
                $action = 'user_change_status';
            } elseif ($user->role === 'production' && $contract->canPerformAction('production_change_status', $user)) {
                $action = 'production_change_status';
            } else {
                \Log::warning('Попытка изменения статуса без прав', [
                    'contract_id' => $contract->id,
                    'user_id' => $user->id,
                    'action' => $action,
                    'user_role' => $user->role
                ]);
                return response()->json(['error' => 'Нет прав для изменения статуса'], 403);
            }
        }

        $oldStatus = $contract->status;
        $contract->status = $newStatus;
        $contract->save();

        // Логируем изменение
        $this->logStatusChange($contract, $oldStatus, $newStatus, $user);

        \Log::info('Статус договора успешно обновлен', [
            'contract_id' => $contract->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Статус обновлен',
            'contract' => $contract->load(['user', 'branch'])
        ]);
    }

    /**
     * Получить данные для канбан-доски через AJAX
     */
    public function getKanbanData(Request $request)
    {
        $user = Auth::user();
        $branchId = $request->get('branch_id');
        $userId = $request->get('user_id');

        // Определяем фильтры в зависимости от роли
        if ($user->role === 'manager') {
            $userId = $user->id;
        } elseif ($user->role === 'rop') {
            $branchId = $user->branch_id;
        }

        $contracts = Contract::getKanbanContracts($branchId, $userId);

        return response()->json($contracts);
    }

    /**
     * Получить месячные данные для графиков
     */
    private function getMonthlyData($branchId, $period)
    {
        $query = Contract::query();
        
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($period !== 'all') {
            $startDate = now()->startOf($period);
            $endDate = now()->endOf($period);
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count, status')
                    ->groupBy('date', 'status')
                    ->orderBy('date')
                    ->get()
                    ->groupBy('date');
    }

    /**
     * Получить топ менеджеров
     */
    private function getTopManagers($branchId, $period)
    {
        $query = Contract::query();
        
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($period !== 'all') {
            $startDate = now()->startOf($period);
            $endDate = now()->endOf($period);
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query
                    ->with('user')
                    ->selectRaw('user_id, COUNT(*) as contracts_count, SUM(order_total) as total_amount')
                    ->groupBy('user_id')
                    ->orderByDesc('contracts_count')
                    ->limit(10)
                    ->get();
    }

    /**
     * Получить последние договоры
     */
    private function getRecentContracts($branchId)
    {
        $query = Contract::with(['user', 'branch']);
        
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->orderByDesc('created_at')
                    ->limit(10)
                    ->get();
    }

    /**
     * Рассчитать KPI
     */
    private function calculateKPIs($branchId, $period)
    {
        $query = Contract::query();
        
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($period !== 'all') {
            $startDate = now()->startOf($period);
            $endDate = now()->endOf($period);
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $contracts = $query->get();

        return [
            'total_contracts' => $contracts->count(),
            'total_amount' => $contracts->sum('order_total'),
            'avg_contract_value' => $contracts->count() > 0 ? round($contracts->avg('order_total'), 2) : 0,
            'conversion_rate' => $this->calculateOverallConversionRate($contracts),
            'avg_processing_time' => $this->calculateAvgProcessingTime($contracts)
        ];
    }

    /**
     * Рассчитать общую конверсию
     */
    private function calculateOverallConversionRate($contracts)
    {
        $total = $contracts->count();
        $completed = $contracts->where('status', Contract::STATUS_COMPLETED)->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    /**
     * Рассчитать среднее время обработки
     */
    private function calculateAvgProcessingTime($contracts)
    {
        $completedContracts = $contracts->where('status', Contract::STATUS_COMPLETED);
        
        if ($completedContracts->count() === 0) {
            return 0;
        }

        $totalDays = 0;
        $validContracts = 0;
        
        foreach ($completedContracts as $contract) {
            // Проверяем, что updated_at и created_at не null
            if ($contract->updated_at && $contract->created_at) {
                $totalDays += $contract->updated_at->diffInDays($contract->created_at);
                $validContracts++;
            }
        }

        return $validContracts > 0 ? round($totalDays / $validContracts, 1) : 0;
    }

    /**
     * Получить действие для статуса
     */
    private function getActionForStatus($status)
    {
        $actions = [
            Contract::STATUS_PENDING_ROP => 'submit_to_rop',
            Contract::STATUS_APPROVED => 'approve',
            Contract::STATUS_IN_PRODUCTION => 'start_production',
            Contract::STATUS_QUALITY_CHECK => 'quality_check',
            Contract::STATUS_READY => 'mark_ready',
            Contract::STATUS_SHIPPED => 'ship',
            Contract::STATUS_COMPLETED => 'complete'
        ];

        return $actions[$status] ?? '';
    }

    /**
     * Рассчитать среднее время для конкретного статуса
     */
    private function calculateAvgTimeForStatus($query, $status)
    {
        $contracts = $query->clone()->where('status', $status)->get();
        
        if ($contracts->count() === 0) {
            return 0;
        }

        $totalDays = 0;
        $validContracts = 0;
        
        foreach ($contracts as $contract) {
            if ($contract->updated_at && $contract->created_at) {
                $totalDays += $contract->updated_at->diffInDays($contract->created_at);
                $validContracts++;
            }
        }

        return $validContracts > 0 ? round($totalDays / $validContracts, 1) : 0;
    }

    /**
     * Получить иконку для статуса
     */
    private function getStatusIcon($status)
    {
        $icons = [
            Contract::STATUS_DRAFT => 'edit',
            Contract::STATUS_PENDING_ROP => 'user-tie',
            Contract::STATUS_APPROVED => 'check-circle',
            Contract::STATUS_REJECTED => 'times-circle',
            Contract::STATUS_ON_HOLD => 'pause-circle',
            Contract::STATUS_IN_PRODUCTION => 'cogs',
            Contract::STATUS_QUALITY_CHECK => 'search',
            Contract::STATUS_READY => 'shipping-fast',
            Contract::STATUS_SHIPPED => 'truck',
            Contract::STATUS_COMPLETED => 'flag-checkered'
        ];

        return $icons[$status] ?? 'circle';
    }

    /**
     * Логировать изменение статуса
     */
    private function logStatusChange($contract, $oldStatus, $newStatus, $user)
    {
        // Здесь можно добавить логирование в отдельную таблицу
        // или использовать существующую систему логов
        \Log::info("Contract {$contract->contract_number} status changed from {$oldStatus} to {$newStatus} by user {$user->name}");
    }

    /**
     * Проверяет, может ли пользователь изменить статус договора
     */
    private function canRopChangeStatus(Contract $contract, User $user, string $newStatus): bool
    {
        // Проверяем права в зависимости от роли
        if ($user->role === 'rop') {
            // РОП может изменять статусы только для договоров своего филиала
            if ($contract->branch_id !== $user->branch_id) {
                return false;
            }
        } elseif ($user->role === 'manager') {
            // Менеджер может изменять статусы только для своих договоров
            if ($contract->user_id !== $user->id) {
                return false;
            }
        }

        // Определяем разрешенные переходы для разных ролей
        $allowedTransitions = [
            Contract::STATUS_PENDING_ROP => [Contract::STATUS_APPROVED, Contract::STATUS_REJECTED, Contract::STATUS_ON_HOLD, Contract::STATUS_RETURNED],
            Contract::STATUS_APPROVED => [Contract::STATUS_IN_PRODUCTION, Contract::STATUS_REJECTED, Contract::STATUS_ON_HOLD],
            Contract::STATUS_IN_PRODUCTION => [Contract::STATUS_QUALITY_CHECK, Contract::STATUS_ON_HOLD],
            Contract::STATUS_QUALITY_CHECK => [Contract::STATUS_READY, Contract::STATUS_ON_HOLD],
            Contract::STATUS_READY => [Contract::STATUS_SHIPPED, Contract::STATUS_ON_HOLD],
            Contract::STATUS_SHIPPED => [Contract::STATUS_COMPLETED, Contract::STATUS_ON_HOLD],
        ];

        $currentStatus = $contract->status;
        return isset($allowedTransitions[$currentStatus]) && in_array($newStatus, $allowedTransitions[$currentStatus]);
    }
}
