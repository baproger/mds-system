<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\ContractStateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Added missing import for User model

class ContractWorkflowController extends Controller
{
    protected $stateService;

    public function __construct(ContractStateService $stateService)
    {
        $this->stateService = $stateService;
    }

    /**
     * Отправить договор на рассмотрение РОП
     */
    public function submitToRop(Request $request, Contract $contract)
    {
        try {
            $this->stateService->submitToRop($contract, Auth::user());
            
            return redirect()->back()->with('success', 'Договор отправлен на проверку РОП');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Одобрить договор
     */
    public function approve(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $this->stateService->approve($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор одобрен');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Отклонить договор
     */
    public function reject(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        try {
            $this->stateService->reject($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор отклонен');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Приостановить договор
     */
    public function hold(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        try {
            $this->stateService->hold($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор приостановлен');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Вернуть на доработку
     */
    public function returnForRevision(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        try {
            $this->stateService->returnForRevision($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор возвращен на доработку');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Начать производство
     */
    public function startProduction(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $this->stateService->startProduction($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Производство начато');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Отправить на контроль качества
     */
    public function qualityCheck(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $this->stateService->qualityCheck($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор отправлен на контроль качества');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Отметить как готовый к отгрузке
     */
    public function markReady(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $this->stateService->markReady($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор готов к отгрузке');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Отметить как отгруженный
     */
    public function ship(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $this->stateService->ship($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор отмечен как отгруженный');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Завершить договор
     */
    public function complete(Request $request, Contract $contract)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $this->stateService->complete($contract, Auth::user(), $request->comment);
            
            return redirect()->back()->with('success', 'Договор завершен');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Показать историю изменений договора
     */
    public function history(Contract $contract)
    {
        $user = Auth::user();
        
        // Админ всегда имеет доступ ко всем договорам
        if ($user->role === 'admin') {
            // Продолжаем выполнение без дополнительных проверок
        } else {
            // Проверяем права доступа для других ролей
            if ($user->role === 'manager') {
                // Менеджер может видеть историю своих договоров и договоров в рамках workflow
                if ($contract->user_id !== $user->id && !$this->canManagerAccessWorkflow($contract, $user)) {
                    abort(403, 'Доступ запрещен. Договор не принадлежит вам или не входит в ваш workflow.');
                }
            } elseif ($user->role === 'rop') {
                // РОП может видеть историю всех договоров своего филиала
                if ($contract->branch_id !== $user->branch_id) {
                    abort(403, 'Доступ запрещен. Договор не принадлежит вашему филиалу.');
                }
            } else {
                abort(403, 'Доступ запрещен. Неизвестная роль.');
            }
        }

        $changes = $contract->changes()
            ->with(['user'])
            ->orderBy('changed_at', 'desc')
            ->get()
            ->groupBy('version_to');

        $approvals = $contract->approvals()
            ->with(['createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('contracts.history', compact('contract', 'changes', 'approvals'));
    }

    /**
     * Проверяет, может ли менеджер получить доступ к договору в рамках workflow
     */
    private function canManagerAccessWorkflow(Contract $contract, User $user): bool
    {
        // Менеджер может видеть договоры, которые он создал или которые находятся в workflow
        if ($contract->user_id === $user->id) {
            return true;
        }
        
        // Менеджер может видеть договоры своего филиала в определенных статусах workflow
        if ($contract->branch_id === $user->branch_id) {
            $workflowStatuses = [
                \App\Models\Contract::STATUS_PENDING_ROP,
                \App\Models\Contract::STATUS_APPROVED,
                \App\Models\Contract::STATUS_IN_PRODUCTION,
                \App\Models\Contract::STATUS_QUALITY_CHECK,
                \App\Models\Contract::STATUS_READY,
                \App\Models\Contract::STATUS_SHIPPED,
                \App\Models\Contract::STATUS_COMPLETED
            ];
            
            return in_array($contract->status, $workflowStatuses);
        }
        
        return false;
    }
}
