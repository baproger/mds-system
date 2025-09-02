<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\ContractStateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Проверяем права доступа
        if (!Auth::user()->can('view', $contract)) {
            abort(403);
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
}
