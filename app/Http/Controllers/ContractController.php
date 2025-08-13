<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Branch;
use App\Services\ContractService;
use App\Services\ContractCalculationService;
use App\Services\ContractStateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['user', 'branch']);
        $user = Auth::user();
        
        // Ограничение по ролям
        if ($user->role === 'manager') {
            // Обычный менеджер видит только свои договоры
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'rop') {
            // РОП видит все договоры своего филиала
            $query->where('branch_id', $user->branch_id);
        } elseif ($user->role === 'admin') {
            // Админ видит все договоры
        } else {
            abort(403);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $contracts = $query->latest()->paginate(20);
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $calculationService = new ContractCalculationService();
        
        $branches = Branch::all();
        $managers = ['Самал', 'Арман', 'Даулет', 'Зухра', 'Фатима', 'Арай', 'Ербол', 'Тоқмұсбек', 'Сымбат', 'Алия', 'Лайла', 'Абылай'];
        $price = $calculationService->getPrice();
        $fusionModels = $calculationService->getFusionModels();
        $userBranch = Auth::user()->branch;
        
        // Добавляем отладочную информацию
        \Log::info('ContractController@create called', [
            'route_name' => request()->route()->getName(),
            'user_role' => Auth::user()->role,
            'route_is_admin' => request()->routeIs('admin.*'),
            'route_is_manager' => request()->routeIs('manager.*'),
            'route_is_rop' => request()->routeIs('rop.*'),
        ]);
        
        // Проверяем, если это админский маршрут
        if (request()->routeIs('admin.*')) {
            return view('admin.contracts.create', compact('branches', 'managers', 'price', 'fusionModels', 'userBranch', 'calculationService'));
        }
        
        // Для менеджера и РОП используем админский шаблон
        if (request()->routeIs('manager.*') || request()->routeIs('rop.*')) {
            return view('admin.contracts.create', compact('branches', 'managers', 'price', 'fusionModels', 'userBranch', 'calculationService'));
        }
        
        return view('contracts.create', compact('branches', 'managers', 'price', 'fusionModels', 'userBranch', 'calculationService'));
    }

    public function store(Request $request)
    {
        $calculationService = new ContractCalculationService();
        
        // Используем валидацию из сервиса
        $validationErrors = $calculationService->validateContract($request->all());
        if (!empty($validationErrors)) {
            return back()->withErrors(['validation' => $validationErrors])->withInput();
        }

        try {
            $validated = $request->validate([
                'contract_number' => 'required|string|unique:contracts,contract_number',
                'manager' => 'required',
                'client' => 'required',
                'instagram' => 'required',
                'iin' => 'required|size:12',
                'phone' => 'required',
                'phone2' => 'required',
                'address' => 'nullable|string',
                'payment' => 'nullable|string',
                'date' => 'required|date',
                'category' => 'required|in:Lux,Premium,Comfort',
                'model' => 'required',
                'width' => 'required|numeric|min:850',
                'height' => 'required|numeric|min:850',
                'design' => 'nullable|string',
                'leaf' => 'required',
                'framugawidth' => 'required',
                'framugaheight' => 'required',
                'forging' => 'nullable|string',
                'opening' => 'nullable|string',
                'frame' => 'nullable|string',
                'outer_panel' => 'nullable|string',
                'outer_cover' => 'required',
                'outer_cover_color' => 'nullable|string',
                'metal_cover_hidden' => 'nullable|string',
                'metal_cover_color' => 'nullable|string',
                'inner_trim' => 'required',
                'inner_cover' => 'required',
                'inner_trim_color' => 'nullable|string',
                'glass_unit' => 'required',
                'extra' => 'nullable|string',
                'lock' => 'required',
                'handle' => 'required',
                'steel_thickness' => 'nullable|string',
                'canvas_thickness' => 'nullable|string',
                'measurement' => 'nullable|string',
                'delivery' => 'nullable|string',
                'installation' => 'nullable|string',
                'order_total' => 'required|numeric|min:0',
                'order_deposit' => 'required|numeric|min:0',
                'order_remainder' => 'required|numeric|min:0',
                'order_due' => 'required|numeric|min:0',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'attachment' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['branch_id'] = Auth::user()->branch_id;

        // Используем методы из нового сервиса
        $validated['outer_panel'] = $calculationService->getOuterPanel($validated['category'], $validated['model']);
        $validated['steel_thickness'] = $calculationService->getSteelThickness($validated['category']);
        $validated['canvas_thickness'] = $calculationService->getCanvasThickness($validated['category'], $validated['model']);
        $validated['extra'] = $calculationService->calculateExtra($validated);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('contracts/photos', 'public');
        }

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('contracts/attachments', 'public');
        }

        Contract::create($validated);

        // Перенаправляем в зависимости от маршрута
        if (request()->routeIs('admin.*')) {
            return redirect()->route('admin.contracts.index')->with('success', 'Договор создан успешно!');
        } elseif (request()->routeIs('manager.*')) {
            return redirect()->route('manager.contracts.index')->with('success', 'Договор создан успешно!');
        } elseif (request()->routeIs('rop.*')) {
            return redirect()->route('rop.contracts.index')->with('success', 'Договор создан успешно!');
        }
        
        return redirect()->route('contracts.index')->with('success', 'Договор создан успешно!');
    }

    public function show(Contract $contract)
    {
        $user = Auth::user();
        
        // Проверяем права доступа
        if ($user->role === 'manager') {
            // Обычный менеджер может видеть только свои договоры
            if ($contract->user_id !== $user->id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вам.');
            }
        } elseif ($user->role === 'rop') {
            // РОП может видеть договоры своего филиала
            if ($contract->branch_id !== $user->branch_id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вашему филиалу.');
            }
        } elseif ($user->role !== 'admin') {
            abort(403);
        }
        
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $user = Auth::user();
        
        // Проверяем права доступа
        if ($user->role === 'manager') {
            // Обычный менеджер может редактировать только свои договоры
            if ($contract->user_id !== $user->id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вам.');
            }
        } elseif ($user->role === 'rop') {
            // РОП может редактировать договоры своего филиала
            if ($contract->branch_id !== $user->branch_id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вашему филиалу.');
            }
        } elseif ($user->role !== 'admin') {
            abort(403);
        }
        
        $branches = Branch::all();
        $managers = ContractService::$managers;
        $price = ContractService::$price;
        $fusionModels = ContractService::$fusionModels;
        
        // Проверяем, если это админский маршрут
        if (request()->routeIs('admin.*')) {
            return view('admin.contracts.edit', compact('contract', 'branches', 'managers', 'price', 'fusionModels'));
        }
        
        // Для менеджера и РОП используем админский шаблон
        if (request()->routeIs('manager.*') || request()->routeIs('rop.*')) {
            return view('admin.contracts.edit', compact('contract', 'branches', 'managers', 'price', 'fusionModels'));
        }
        
        return view('contracts.edit', compact('contract', 'branches', 'managers', 'price', 'fusionModels'));
    }

    public function update(Request $request, Contract $contract)
    {
        $user = Auth::user();
        
        // Проверяем права доступа
        if ($user->role === 'manager') {
            // Обычный менеджер может редактировать только свои договоры
            if ($contract->user_id !== $user->id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вам.');
            }
        } elseif ($user->role === 'rop') {
            // РОП может редактировать договоры своего филиала
            if ($contract->branch_id !== $user->branch_id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вашему филиалу.');
            }
        } elseif ($user->role !== 'admin') {
            abort(403);
        }

        try {
            $validated = $request->validate([
                'contract_number' => 'required|string|unique:contracts,contract_number,' . $contract->id,
                'client' => 'required',
                'instagram' => 'required',
                'iin' => 'required|size:12',
                'phone' => 'required',
                'phone2' => 'required',
                'date' => 'required|date',
                'category' => 'required|in:Lux,Premium,Comfort',
                'model' => 'required',
                'width' => 'required|numeric|min:850',
                'height' => 'required|numeric|min:850',
                'leaf' => 'nullable',
                'framugawidth' => 'required',
                'framugaheight' => 'required',
                'outer_cover' => 'required',
                'inner_trim' => 'required',
                'inner_cover' => 'required',
                'glass_unit' => 'required',
                'lock' => 'required',
                'handle' => 'required',
                'order_total' => 'required|numeric|min:0',
                'order_deposit' => 'required|numeric|min:0',
                'order_remainder' => 'required|numeric|min:0',
                'order_due' => 'required|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }

        // Проверка Fusion модели
        $fusionError = ContractService::validateFusionModel($validated['model'], $validated['outer_cover']);
        if ($fusionError) {
            return back()->withErrors(['outer_cover' => $fusionError])->withInput();
        }

        // Проверка ИИН
        if (!preg_match('/^\d{12}$/', $validated['iin'])) {
            return back()->withErrors(['iin' => 'ИИН клиента должен состоять ровно из 12 цифр.'])->withInput();
        }

        // Проверка модели для категории
        if (!isset(ContractService::$price[$validated['category']][$validated['model']])) {
            return back()->withErrors(['model' => 'Неверная модель для выбранной категории.'])->withInput();
        }

        // Проверка диапазона номера договора
        $rangeError = ContractService::validateContractNumberRange($validated['contract_number'], $contract->branch_id);
        if ($rangeError) {
            return back()->withErrors(['contract_number' => $rangeError])->withInput();
        }

        $validated['outer_panel'] = ContractService::getOuterPanel($validated['category'], $validated['model']);
        $validated['steel_thickness'] = ContractService::getSteelThickness($validated['category']);
        $validated['canvas_thickness'] = ContractService::getCanvasThickness($validated['category'], $validated['model']);
        $validated['extra'] = ContractService::calculateExtra(
            $validated['category'], 
            $validated['inner_trim'], 
            $validated['inner_cover'], 
            $validated['glass_unit']
        );

        if ($request->hasFile('photo')) {
            if ($contract->photo_path) {
                Storage::disk('public')->delete($contract->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('contracts/photos', 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($contract->attachment_path) {
                Storage::disk('public')->delete($contract->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('contracts/attachments', 'public');
        }

        // Логируем изменения перед обновлением
        $changes = [];
        foreach ($validated as $field => $newValue) {
            if ($contract->getAttribute($field) != $newValue) {
                $changes[$field] = [
                    'old' => $contract->getAttribute($field),
                    'new' => $newValue
                ];
            }
        }

        // Обновляем договор
        $contract->update($validated);

        // Логируем изменения если они есть
        if (!empty($changes)) {
            $stateService = new ContractStateService();
            $stateService->logChanges($contract, $changes, $user);
        }

        // Перенаправляем в зависимости от маршрута
        if (request()->routeIs('admin.*')) {
            return redirect()->route('admin.contracts.index')->with('success', 'Договор обновлен успешно!');
        } elseif (request()->routeIs('manager.*')) {
            return redirect()->route('manager.contracts.index')->with('success', 'Договор обновлен успешно!');
        } elseif (request()->routeIs('rop.*')) {
            return redirect()->route('rop.contracts.index')->with('success', 'Договор обновлен успешно!');
        }
        
        return redirect()->route('contracts.index')->with('success', 'Договор обновлен успешно!');
    }

    public function destroy(Contract $contract)
    {
        $user = Auth::user();
        
        // Проверяем права доступа
        if ($user->role === 'manager') {
            // Обычный менеджер может удалять только свои договоры
            if ($contract->user_id !== $user->id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вам.');
            }
        } elseif ($user->role === 'rop') {
            // РОП может удалять договоры своего филиала
            if ($contract->branch_id !== $user->branch_id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вашему филиалу.');
            }
        } elseif ($user->role !== 'admin') {
            abort(403);
        }

        if ($contract->photo_path) {
            Storage::disk('public')->delete($contract->photo_path);
        }
        if ($contract->attachment_path) {
            Storage::disk('public')->delete($contract->attachment_path);
        }

        $contract->delete();

        // Перенаправляем в зависимости от маршрута
        if (request()->routeIs('admin.*')) {
            return redirect()->route('admin.contracts.index')->with('success', 'Договор удален успешно!');
        } elseif (request()->routeIs('manager.*')) {
            return redirect()->route('manager.contracts.index')->with('success', 'Договор удален успешно!');
        } elseif (request()->routeIs('rop.*')) {
            return redirect()->route('rop.contracts.index')->with('success', 'Договор удален успешно!');
        }
        
        return redirect()->route('contracts.index')->with('success', 'Договор удален успешно!');
    }



    // Печать договора
    public function print(Contract $contract)
    {
        $user = Auth::user();
        
        // Проверяем права доступа
        if ($user->role === 'manager') {
            // Обычный менеджер может печатать только свои договоры
            if ($contract->user_id !== $user->id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вам.');
            }
        } elseif ($user->role === 'rop') {
            // РОП может печатать договоры своего филиала
            if ($contract->branch_id !== $user->branch_id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вашему филиалу.');
            }
        } elseif ($user->role !== 'admin') {
            abort(403);
        }
        
        return view('contracts.print', compact('contract'));
    }

    // Экспорт в Word
    public function exportWord(Contract $contract)
    {
        $user = Auth::user();
        
        // Проверяем права доступа
        if ($user->role === 'manager') {
            // Обычный менеджер может экспортировать только свои договоры
            if ($contract->user_id !== $user->id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вам.');
            }
        } elseif ($user->role === 'rop') {
            // РОП может экспортировать договоры своего филиала
            if ($contract->branch_id !== $user->branch_id) {
                abort(403, 'Доступ запрещен. Договор не принадлежит вашему филиалу.');
            }
        } elseif ($user->role !== 'admin') {
            abort(403);
        }

        // Генерируем HTML контент
        $html = view('contracts.print', compact('contract'))->render();
        
        // Удаляем любые кнопки управления из HTML
        $html = preg_replace('/<div class="print-controls[^>]*>.*?<\/div>/s', '', $html);
        $html = preg_replace('/<div class="no-print[^>]*>.*?<\/div>/s', '', $html);
        $html = preg_replace('/<button[^>]*>.*?<\/button>/s', '', $html);
        
        // Создаем имя файла
        $filename = 'Договор_' . $contract->contract_number . '_' . date('Y-m-d') . '.doc';
        
        // Возвращаем файл для скачивания
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}
