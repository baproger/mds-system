<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagerController extends Controller
{
    public function index(Request $request)
    {
        // Получаем только пользователей с ролями manager и rop
        $query = User::with(['branch'])
            ->whereIn('role', ['manager', 'rop'])
            ->withCount(['contracts'])
            ->withSum('contracts', 'order_total')
            ->withCount(['contracts as contracts_this_month' => function($query) {
                $query->whereMonth('created_at', now()->month);
            }]);

        // Фильтр по филиалу
        if ($request->filled('branch')) {
            $query->where('branch_id', $request->branch);
        }

        // Фильтр по роли
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Поиск по имени
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $managers = $query->latest()->paginate(20)->withQueryString();
        $branches = Branch::all();

        return view('admin.managers.index', compact('managers', 'branches'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.managers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'role' => 'required|in:manager,rop',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Создаем пользователя-менеджера
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'branch_id' => $validated['branch_id'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $roleText = $validated['role'] === 'rop' ? 'РОП' : 'Менеджер';
        return redirect()->route('admin.managers.index')->with('success', "{$roleText} создан успешно!");
    }

    public function edit(User $manager)
    {
        $branches = Branch::all();
        return view('admin.managers.edit', compact('manager', 'branches'));
    }

    public function update(Request $request, User $manager)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'role' => 'required|in:manager,rop',
            'email' => 'required|email|unique:users,email,' . $manager->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'branch_id' => $validated['branch_id'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $manager->update($updateData);

        $roleText = $validated['role'] === 'rop' ? 'РОП' : 'Менеджер';
        return redirect()->route('admin.managers.index')->with('success', "{$roleText} обновлен успешно!");
    }

    public function destroy(User $manager)
    {
        // Проверяем, есть ли у менеджера договоры
        if ($manager->contracts()->count() > 0) {
            return back()->with('error', "Нельзя удалить менеджера '{$manager->name}' - у него есть договоры!");
        }

        $managerName = $manager->name;
        $roleText = $manager->role === 'rop' ? 'РОП' : 'Менеджер';

        $manager->delete();

        return redirect()->route('admin.managers.index')->with('success', "{$roleText} '{$managerName}' удален успешно!");
    }

    public function show(User $manager)
    {
        // Подгружаем филиал, список договоров (для карточки последних) и агрегаты по договорам
        $manager->load([
            'branch',
            'contracts' => function($query) {
                $query->latest();
            }
        ]);

        // Счётчики и суммы для отображения статистики
        $manager->loadCount(['contracts']);
        $manager->loadSum('contracts', 'order_total');

        return view('admin.managers.show', compact('manager'));
    }
}
