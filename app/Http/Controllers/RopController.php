<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RopController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $branch = $user->branch;
        
        if (!$branch) {
            abort(403, 'РОП не привязан к филиалу.');
        }

        $stats = [
            'total_contracts' => Contract::where('branch_id', $branch->id)->count(),
            'total_users' => User::where('branch_id', $branch->id)->count(),
            'total_managers' => User::where('branch_id', $branch->id)->whereIn('role', ['manager', 'rop'])->count(),
            'contracts_this_month' => Contract::where('branch_id', $branch->id)->whereMonth('created_at', now()->month)->count(),
            'contracts_this_year' => Contract::where('branch_id', $branch->id)->whereYear('created_at', now()->year)->count(),
            'revenue_this_month' => Contract::where('branch_id', $branch->id)->whereMonth('created_at', now()->month)->sum('order_total'),
            'revenue_this_year' => Contract::where('branch_id', $branch->id)->whereYear('created_at', now()->year)->sum('order_total'),
        ];

        $recent_contracts = Contract::with(['user', 'branch'])
            ->where('branch_id', $branch->id)
            ->latest()
            ->take(10)
            ->get();

        $managers = User::where('branch_id', $branch->id)
            ->whereIn('role', ['manager', 'rop'])
            ->get();

        $users = User::where('branch_id', $branch->id)->get();

        return view('rop.dashboard', compact('stats', 'recent_contracts', 'managers', 'users', 'branch'));
    }

    public function contracts(Request $request)
    {
        $user = Auth::user();
        $branch = $user->branch;
        
        if (!$branch) {
            abort(403, 'РОП не привязан к филиалу.');
        }

        $query = Contract::with(['user', 'branch'])
            ->where('branch_id', $branch->id);

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Фильтр по менеджеру
        if ($request->filled('manager')) {
            $query->where('user_id', $request->manager);
        }

        // Фильтр по дате
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $contracts = $query->latest()->paginate(20)->withQueryString();
        $managers = User::where('branch_id', $branch->id)->whereIn('role', ['manager', 'rop'])->get();

        return view('rop.contracts.index', compact('contracts', 'managers', 'branch'));
    }

    public function showContract(Contract $contract)
    {
        $user = Auth::user();
        
        // Проверяем, что контракт принадлежит филиалу РОП
        if ($contract->branch_id !== $user->branch_id) {
            abort(403, 'Доступ запрещен. Контракт не принадлежит вашему филиалу.');
        }

        return view('rop.contracts.show', compact('contract'));
    }

    public function users(Request $request)
    {
        $user = Auth::user();
        $branch = $user->branch;
        
        if (!$branch) {
            abort(403, 'РОП не привязан к филиалу.');
        }

        $query = User::with('branch')->withCount('contracts')
            ->where('branch_id', $branch->id);

        // Поиск по имени или email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Фильтр по роли
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(20)->withQueryString();
        
        return view('rop.users.index', compact('users', 'branch'));
    }

    public function managers(Request $request)
    {
        $user = Auth::user();
        $branch = $user->branch;
        
        if (!$branch) {
            abort(403, 'РОП не привязан к филиалу.');
        }

        $query = User::with('branch')->withCount('contracts')
            ->where('branch_id', $branch->id)
            ->whereIn('role', ['manager', 'rop']);

        // Фильтр по роли
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Поиск по имени
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $managers = $query->latest()->paginate(20)->withQueryString();
        
        return view('rop.managers.index', compact('managers', 'branch'));
    }

    public function showManager(User $manager)
    {
        $user = Auth::user();
        
        // Проверяем, что менеджер принадлежит филиалу РОП
        if ($manager->branch_id !== $user->branch_id) {
            abort(403, 'Доступ запрещен. Менеджер не принадлежит вашему филиалу.');
        }

        // Проверяем, что это действительно менеджер или РОП
        if (!in_array($manager->role, ['manager', 'rop'])) {
            abort(403, 'Доступ запрещен. Пользователь не является менеджером или РОП.');
        }

        $manager->load(['branch', 'contracts' => function($query) {
            $query->latest()->take(10);
        }]);
        
        return view('rop.managers.show', compact('manager'));
    }

    public function createManager()
    {
        $user = Auth::user();
        $branch = $user->branch;
        
        if (!$branch) {
            abort(403, 'РОП не привязан к филиалу.');
        }

        return view('rop.managers.create', compact('branch'));
    }

    public function storeManager(Request $request)
    {
        $user = Auth::user();
        $branch = $user->branch;
        
        if (!$branch) {
            abort(403, 'РОП не привязан к филиалу.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:manager',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['branch_id'] = $branch->id;

        User::create($validated);

        return redirect()->route('rop.managers.index')->with('success', 'Менеджер создан успешно!');
    }

    public function editManager(User $manager)
    {
        $user = Auth::user();
        
        // Проверяем, что менеджер принадлежит филиалу РОП
        if ($manager->branch_id !== $user->branch_id) {
            abort(403, 'Доступ запрещен. Менеджер не принадлежит вашему филиалу.');
        }

        // Проверяем, что это действительно менеджер или РОП
        if (!in_array($manager->role, ['manager', 'rop'])) {
            abort(403, 'Доступ запрещен. Пользователь не является менеджером или РОП.');
        }

        $branch = $user->branch;
        
        return view('rop.managers.edit', compact('manager', 'branch'));
    }

    public function updateManager(Request $request, User $manager)
    {
        $user = Auth::user();
        
        // Проверяем, что менеджер принадлежит филиалу РОП
        if ($manager->branch_id !== $user->branch_id) {
            abort(403, 'Доступ запрещен. Менеджер не принадлежит вашему филиалу.');
        }

        // Проверяем, что это действительно менеджер или РОП
        if (!in_array($manager->role, ['manager', 'rop'])) {
            abort(403, 'Доступ запрещен. Пользователь не является менеджером или РОП.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $manager->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:manager,rop',
            'phone' => 'nullable|string|max:20',
        ]);

        // РОП может изменять только на роль менеджера, если текущий пользователь не РОП
        if ($manager->role !== 'rop' && $validated['role'] === 'rop') {
            abort(403, 'РОП может изменять только на роль менеджера.');
        }

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // РОП не может изменить филиал менеджера
        $validated['branch_id'] = $user->branch_id;

        $manager->update($validated);

        return redirect()->route('rop.managers.index')->with('success', 'Менеджер обновлен успешно!');
    }

    public function deleteManager(User $manager)
    {
        $user = Auth::user();
        
        // Проверяем, что менеджер принадлежит филиалу РОП
        if ($manager->branch_id !== $user->branch_id) {
            abort(403, 'Доступ запрещен. Менеджер не принадлежит вашему филиалу.');
        }

        // Проверяем, что это действительно менеджер или РОП
        if (!in_array($manager->role, ['manager', 'rop'])) {
            abort(403, 'Доступ запрещен. Пользователь не является менеджером или РОП.');
        }

        // РОП не может удалить самого себя
        if ($manager->id === $user->id) {
            return back()->with('error', 'Нельзя удалить самого себя!');
        }

        $managerName = $manager->name;
        $manager->delete();

        return redirect()->route('rop.managers.index')->with('success', "Менеджер '{$managerName}' удален успешно!");
    }
}
