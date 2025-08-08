<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Статистика для менеджера (только его филиал и его договоры)
        $stats = [
            // Общие статистики (нужны для совместимости с view)
            'total_users' => User::where('branch_id', $user->branch_id)->count(),
            'total_branches' => 1, // У менеджера только один филиал
            'total_sales_staff' => User::where('branch_id', $user->branch_id)
                ->whereIn('role', ['manager', 'rop'])->count(),
            'total_rop' => User::where('branch_id', $user->branch_id)
                ->where('role', 'rop')->count(),
            'total_managers' => User::where('branch_id', $user->branch_id)
                ->where('role', 'manager')->count(),
            'contracts_this_year' => Contract::where('branch_id', $user->branch_id)
                ->whereYear('created_at', now()->year)->count(),
            
            // Специфичные для менеджера статистики
            'total_contracts' => Contract::where('branch_id', $user->branch_id)->count(),
            'my_contracts' => Contract::where('branch_id', $user->branch_id)
                ->where('user_id', $user->id)->count(),
            'contracts_pending' => Contract::where('branch_id', $user->branch_id)
                ->where('status', 'pending')->count(),
            'contracts_approved' => Contract::where('branch_id', $user->branch_id)
                ->where('status', 'approved')->count(),
            'contracts_completed' => Contract::where('branch_id', $user->branch_id)
                ->where('status', 'completed')->count(),
            'contracts_cancelled' => Contract::where('branch_id', $user->branch_id)
                ->where('status', 'cancelled')->count(),
            'contracts_this_month' => Contract::where('branch_id', $user->branch_id)
                ->whereMonth('created_at', now()->month)->count(),
            'revenue_this_month' => Contract::where('branch_id', $user->branch_id)
                ->whereMonth('created_at', now()->month)->sum('order_total') ?? 0,
            'revenue_this_year' => Contract::where('branch_id', $user->branch_id)
                ->whereYear('created_at', now()->year)->sum('order_total') ?? 0,
        ];

        // Последние договоры менеджера
        $recent_contracts = Contract::with(['user', 'branch'])
            ->where('branch_id', $user->branch_id)
            ->latest()
            ->take(10)
            ->get();

        // Статистика по статусам договоров
        $contracts_by_status = Contract::where('branch_id', $user->branch_id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // Филиал менеджера
        $branch = Branch::find($user->branch_id);
        
        // Добавляем недостающие переменные для совместимости с view
        $branches = collect([$branch]); // Только один филиал для менеджера
        $users_by_role = collect(); // Пустая коллекция для менеджера
        $managers_by_role = collect(); // Пустая коллекция для менеджера

        return view('admin.dashboard', compact('stats', 'recent_contracts', 'branches', 'users_by_role', 'managers_by_role', 'contracts_by_status'));
    }
} 