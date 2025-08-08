<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Админ видит всю статистику
            $stats = [
                'total_contracts' => Contract::count(),
                'total_users' => User::count(),
                'total_branches' => Branch::count(),
                'total_sales_staff' => User::salesStaff()->count(),
                'total_managers' => User::where('role', 'manager')->count(),
                'total_rop' => User::where('role', 'rop')->count(),
                'contracts_this_month' => Contract::whereMonth('created_at', now()->month)->count(),
                'contracts_this_year' => Contract::whereYear('created_at', now()->year)->count(),
                'revenue_this_month' => Contract::whereMonth('created_at', now()->month)->sum('order_total') ?? 0,
                'revenue_this_year' => Contract::whereYear('created_at', now()->year)->sum('order_total') ?? 0,
            ];

            $recent_contracts = Contract::with(['user', 'branch'])
                ->latest()
                ->take(10)
                ->get();

            $branches = Branch::withCount(['contracts', 'users'])
                ->get()
                ->map(function($branch) {
                    $branch->managers_count = User::where('branch_id', $branch->id)
                        ->whereIn('role', ['manager', 'rop'])
                        ->count();
                    return $branch;
                });

        } elseif ($user->role === 'manager') {
            // Менеджер видит только свою статистику
            $stats = [
                'total_contracts' => Contract::where('user_id', $user->id)->count(),
                'total_users' => 1, // Только себя
                'total_branches' => 1, // Только свой филиал
                'total_sales_staff' => 1,
                'total_managers' => 1,
                'total_rop' => 0,
                'contracts_this_month' => Contract::where('user_id', $user->id)->whereMonth('created_at', now()->month)->count(),
                'contracts_this_year' => Contract::where('user_id', $user->id)->whereYear('created_at', now()->year)->count(),
                'revenue_this_month' => Contract::where('user_id', $user->id)->whereMonth('created_at', now()->month)->sum('order_total') ?? 0,
                'revenue_this_year' => Contract::where('user_id', $user->id)->whereYear('created_at', now()->year)->sum('order_total') ?? 0,
            ];

            $recent_contracts = Contract::where('user_id', $user->id)
                ->with(['user', 'branch'])
                ->latest()
                ->take(10)
                ->get();

            $branches = collect([$user->branch])->map(function($branch) use ($user) {
                $branch->contracts_count = Contract::where('user_id', $user->id)->count();
                $branch->users_count = 1;
                $branch->managers_count = 1;
                return $branch;
            });

        } elseif ($user->role === 'rop') {
            // РОП видит статистику своего филиала
            $stats = [
                'total_contracts' => Contract::whereHas('user', function($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                })->count(),
                'total_users' => User::where('branch_id', $user->branch_id)->count(),
                'total_branches' => 1,
                'total_sales_staff' => User::where('branch_id', $user->branch_id)->whereIn('role', ['manager', 'rop'])->count(),
                'total_managers' => User::where('branch_id', $user->branch_id)->where('role', 'manager')->count(),
                'total_rop' => User::where('branch_id', $user->branch_id)->where('role', 'rop')->count(),
                'contracts_this_month' => Contract::whereHas('user', function($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                })->whereMonth('created_at', now()->month)->count(),
                'contracts_this_year' => Contract::whereHas('user', function($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                })->whereYear('created_at', now()->year)->count(),
                'revenue_this_month' => Contract::whereHas('user', function($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                })->whereMonth('created_at', now()->month)->sum('order_total') ?? 0,
                'revenue_this_year' => Contract::whereHas('user', function($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                })->whereYear('created_at', now()->year)->sum('order_total') ?? 0,
            ];

            $recent_contracts = Contract::whereHas('user', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            })->with(['user', 'branch'])
                ->latest()
                ->take(10)
                ->get();

            $branches = collect([$user->branch])->map(function($branch) use ($user) {
                $branch->contracts_count = Contract::whereHas('user', function($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })->count();
                $branch->users_count = User::where('branch_id', $branch->id)->count();
                $branch->managers_count = User::where('branch_id', $branch->id)->whereIn('role', ['manager', 'rop'])->count();
                return $branch;
            });
        }

        $users_by_role = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->get();

        $managers_by_role = User::selectRaw('role, count(*) as count')
            ->whereIn('role', ['manager', 'rop'])
            ->groupBy('role')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_contracts', 'branches', 'users_by_role', 'managers_by_role'));
    }



    public function users(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Админ видит всех пользователей
            $query = User::with(['branch'])->withCount(['contracts']);
        } elseif ($user->role === 'manager') {
            // Менеджер видит только себя
            $query = User::where('id', $user->id)->with(['branch'])->withCount(['contracts']);
        } elseif ($user->role === 'rop') {
            // РОП видит пользователей своего филиала
            $query = User::where('branch_id', $user->branch_id)->with(['branch'])->withCount(['contracts']);
        }

        // Поиск по имени или email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Фильтр по роли (только для админа)
        if ($user->role === 'admin' && $request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Фильтр по филиалу (только для админа)
        if ($user->role === 'admin' && $request->filled('branch')) {
            $query->where('branch_id', $request->branch);
        }

        $users = $query->paginate(20)->withQueryString();
        
        // Показываем фильтры только админу
        if ($user->role === 'admin') {
            $branches = Branch::all();
        } else {
            $branches = collect();
        }
        
        return view('admin.users.index', compact('users', 'branches'));
    }

    public function index()
    {
        return $this->users(request());
    }

    public function usersIndex()
    {
        return $this->users(request());
    }

    public function branchesIndex()
    {
        return $this->branches();
    }

    public function createUser()
    {
        $branches = Branch::all();
        return view('admin.users.create', compact('branches'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,rop,director,accountant',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь создан успешно!');
    }

    public function editUser(User $user)
    {
        $branches = Branch::all();
        return view('admin.users.edit', compact('user', 'branches'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,rop,director,accountant',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь обновлен успешно!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Нельзя удалить самого себя!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Пользователь '{$userName}' удален успешно!");
    }

    public function deleteManager(User $manager)
    {
        if ($manager->id === Auth::id()) {
            return back()->with('error', 'Нельзя удалить самого себя!');
        }

        if (!in_array($manager->role, ['manager', 'rop'])) {
            return back()->with('error', 'Можно удалять только менеджеров и РОП!');
        }

        $managerName = $manager->name;
        $manager->delete();

        return redirect()->route('admin.managers.index')->with('success', "Менеджер '{$managerName}' удален успешно!");
    }

    public function branches()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Админ видит все филиалы
            $branches = Branch::withCount(['contracts', 'users'])
                ->get()
                ->map(function($branch) {
                    $branch->sales_staff_count = User::where('branch_id', $branch->id)
                        ->where('role', 'manager')
                        ->count();
                    return $branch;
                });
        } elseif ($user->role === 'manager') {
            // Менеджер видит только свой филиал
            $branches = collect([$user->branch])->map(function($branch) use ($user) {
                $branch->contracts_count = Contract::where('user_id', $user->id)->count();
                $branch->users_count = 1;
                $branch->sales_staff_count = 1;
                return $branch;
            });
        } elseif ($user->role === 'rop') {
            // РОП видит только свой филиал
            $branches = collect([$user->branch])->map(function($branch) use ($user) {
                $branch->users_count = User::where('branch_id', $branch->id)->count();
                $branch->contracts_count = Contract::whereHas('user', function($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })->count();
                $branch->sales_staff_count = User::where('branch_id', $branch->id)
                    ->where('role', 'manager')
                    ->count();
                return $branch;
            });
        }
        
        return view('admin.branches.index', compact('branches'));
    }

    public function createBranch()
    {
        return view('admin.branches.create');
    }

    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:branches',
            'contract_counter' => 'required|integer|min:1',
        ]);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Филиал создан успешно!');
    }

    public function editBranch(Branch $branch)
    {
        // Загружаем актуальную статистику
        $branch->loadCount(['users', 'contracts']);
        
        // Добавляем подсчет менеджеров
        $branch->sales_staff_count = User::where('branch_id', $branch->id)
            ->where('role', 'manager')
            ->count();
        
        return view('admin.branches.edit', compact('branch'));
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:branches,code,' . $branch->id,
            'contract_counter' => 'required|integer|min:1',
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Филиал обновлен успешно!');
    }

    public function deleteBranch(Branch $branch)
    {
        if ($branch->contracts()->count() > 0) {
            return back()->with('error', "Нельзя удалить филиал '{$branch->name}' - у него есть договоры!");
        }

        if ($branch->users()->count() > 0) {
            return back()->with('error', "Нельзя удалить филиал '{$branch->name}' - у него есть пользователи!");
        }

        $branchName = $branch->name;
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', "Филиал '{$branchName}' удален успешно!");
    }

    public function contracts(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Админ видит все договоры
            $query = Contract::with(['user', 'branch']);
        } elseif ($user->role === 'manager') {
            // Менеджер видит только свои договоры
            $query = Contract::where('user_id', $user->id)->with(['user', 'branch']);
        } elseif ($user->role === 'rop') {
            // РОП видит договоры своего филиала
            $query = Contract::whereHas('user', function($q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            })->with(['user', 'branch']);
        }

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Фильтр по филиалу (только для админа)
        if ($user->role === 'admin' && $request->filled('branch')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }

        // Фильтр по менеджеру (только для админа)
        if ($user->role === 'admin' && $request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        // Фильтр по дате
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $contracts = $query->latest()->paginate(20)->withQueryString();
        
        // Показываем фильтры только админу
        if ($user->role === 'admin') {
            $branches = Branch::all();
            $users = User::where('role', 'manager')->get();
        } else {
            $branches = collect();
            $users = collect();
        }

        return view('admin.contracts.index', compact('contracts', 'branches', 'users'));
    }

    public function showContract(Contract $contract)
    {
        return view('admin.contracts.show', compact('contract'));
    }

    public function deleteContract(Contract $contract)
    {
        $contractNumber = $contract->contract_number;
        $contract->delete();

        return redirect()->route('admin.contracts.index')->with('success', "Договор №{$contractNumber} удален успешно!");
    }

    public function editManager(User $manager)
    {
        // Загружаем статистику менеджера
        $manager->loadCount(['contracts']);
        $manager->loadSum('contracts', 'order_total');
        
        $branches = Branch::all();
        
        return view('admin.managers.edit', compact('manager', 'branches'));
    }
}
