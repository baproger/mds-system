<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminManagerController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ContractWorkflowController;
use App\Http\Controllers\CrmController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get("/", function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'manager') {
            return redirect()->route('manager.dashboard');
        } elseif (Auth::user()->role === 'rop') {
            return redirect()->route('rop.dashboard');
        }
        return redirect()->route("contracts.index");
    }
    return view('welcome');
});

//

Route::middleware("guest")->group(function () {
    Route::get("/login", [AuthController::class, "showLogin"])->name("login");
    Route::post("/login", [AuthController::class, "login"]);
    Route::get("/register", [AuthController::class, "showRegister"])->name("register");
    Route::post("/register", [AuthController::class, "register"]);
});

Route::middleware(['auth', 'user-only'])->group(function () {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
    // Общие маршруты договоров без прав на создание/редактирование
    Route::resource("contracts", ContractController::class)->only(["index", "show"]);
    

});

// Общий logout маршрут для всех авторизованных пользователей
Route::middleware('auth')->group(function () {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
});

Route::middleware("auth")->group(function () {
    Route::get("contracts/{contract}/print", [ContractController::class, "print"])->name("contracts.print");
    Route::get("contracts/{contract}/export-word", [ContractController::class, "exportWord"])->name("contracts.export-word");
});

// Роуты для менеджеров (префикс /manager)
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    // Договоры: список/просмотр/редактирование своих
    Route::get('/contracts', [App\Http\Controllers\AdminController::class, 'contracts'])->name('contracts.index');
    Route::get('/contracts/create', [App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.delete');
    
    // CRM функциональность
    Route::get('/crm', [CrmController::class, 'demo'])->name('crm.demo');
    Route::get('/crm/kanban', [CrmController::class, 'kanban'])->name('crm.kanban');
    Route::get('/crm/dashboard', [CrmController::class, 'dashboard'])->name('crm.dashboard');
    Route::post('/crm/contracts/{contract}/update-status', [CrmController::class, 'updateStatus'])->name('crm.update-status');
    Route::get('/crm/kanban-data', [CrmController::class, 'getKanbanData'])->name('crm.kanban-data');
    
    // Калькулятор дверей
    Route::get('/calculator', [App\Http\Controllers\CalculatorController::class, 'index'])->name('calculator.index');
    
    // Настройки
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/preferences', [App\Http\Controllers\SettingsController::class, 'updatePreferences'])->name('settings.preferences');
    
    // Профиль
    Route::get('/profile', [App\Http\Controllers\SettingsController::class, 'index'])->name('profile.show');
    
    
});

// Роуты для РОП (префикс /rop)
Route::middleware(['auth', 'rop'])->prefix('rop')->name('rop.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    // Договоры: список/просмотр/редактирование по филиалу
    Route::get('/contracts', [App\Http\Controllers\AdminController::class, 'contracts'])->name('contracts.index');
    Route::get('/contracts/create', [App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.delete');
    
    // CRM функциональность
    Route::get('/crm', [CrmController::class, 'demo'])->name('crm.demo');
    Route::get('/crm/kanban', [CrmController::class, 'kanban'])->name('crm.kanban');
    Route::get('/crm/dashboard', [CrmController::class, 'dashboard'])->name('crm.dashboard');
    Route::post('/crm/contracts/{contract}/update-status', [CrmController::class, 'updateStatus'])->name('crm.update-status');
    Route::get('/crm/kanban-data', [CrmController::class, 'getKanbanData'])->name('crm.kanban-data');
    
    // Калькулятор дверей
    Route::get('/calculator', [App\Http\Controllers\CalculatorController::class, 'index'])->name('calculator.index');
    
    // Настройки
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/preferences', [App\Http\Controllers\SettingsController::class, 'updatePreferences'])->name('settings.preferences');
    
    // Профиль
    Route::get('/profile', [App\Http\Controllers\SettingsController::class, 'index'])->name('profile.show');
    
    // Управление менеджерами своего филиала
    Route::get('/managers', [App\Http\Controllers\RopController::class, 'managers'])->name('managers.index');
    Route::get('/managers/create', [App\Http\Controllers\RopController::class, 'createManager'])->name('managers.create');
    Route::post('/managers', [App\Http\Controllers\RopController::class, 'storeManager'])->name('managers.store');
    Route::get('/managers/{manager}', [App\Http\Controllers\RopController::class, 'showManager'])->name('managers.show');
    Route::get('/managers/{manager}/edit', [App\Http\Controllers\RopController::class, 'editManager'])->name('managers.edit');
    Route::put('/managers/{manager}', [App\Http\Controllers\RopController::class, 'updateManager'])->name('managers.update');
    Route::delete('/managers/{manager}', [App\Http\Controllers\RopController::class, 'deleteManager'])->name('managers.delete');
    
    // Управление пользователями своего филиала
    Route::get('/users', [App\Http\Controllers\RopController::class, 'users'])->name('users.index');
});

// Админские маршруты (только для admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Админский дашборд (только для admin)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    

    
    // Админские страницы договоров
    Route::get('/contracts', [App\Http\Controllers\AdminController::class, 'contracts'])->name('contracts.index');
    Route::get('/contracts/create', [App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.delete');
    
    // CRM функциональность
    Route::get('/crm', [CrmController::class, 'demo'])->name('crm.demo');
    Route::get('/crm/kanban', [CrmController::class, 'kanban'])->name('crm.kanban');
    Route::get('/crm/dashboard', [CrmController::class, 'dashboard'])->name('crm.dashboard');
    Route::post('/crm/contracts/{contract}/update-status', [CrmController::class, 'updateStatus'])->name('crm.update-status');
    Route::get('/crm/kanban-data', [CrmController::class, 'getKanbanData'])->name('crm.kanban-data');
    
    // Управление менеджерами и РОП (только для admin)
    Route::get('/managers', [AdminManagerController::class, 'index'])->name('managers.index');
    Route::get('/managers/create', [AdminManagerController::class, 'create'])->name('managers.create');
    Route::post('/managers', [AdminManagerController::class, 'store'])->name('managers.store');
    Route::get('/managers/{manager}', [AdminManagerController::class, 'show'])->name('managers.show');
    Route::get('/managers/{manager}/edit', [AdminManagerController::class, 'edit'])->name('managers.edit');
    Route::put('/managers/{manager}', [AdminManagerController::class, 'update'])->name('managers.update');
    Route::delete('/managers/{manager}', [AdminController::class, 'deleteManager'])->name('managers.delete');
    // Управление пользователями (только для admin)
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Управление филиалами (только для admin)
    Route::get('/branches', [AdminController::class, 'branchesIndex'])->name('branches.index');
    Route::get('/branches/create', [AdminController::class, 'createBranch'])->name('branches.create');
    Route::post('/branches', [AdminController::class, 'storeBranch'])->name('branches.store');
    Route::get('/branches/{branch}/edit', [AdminController::class, 'editBranch'])->name('branches.edit');
    Route::put('/branches/{branch}', [AdminController::class, 'updateBranch'])->name('branches.update');
    Route::delete('/branches/{branch}', [AdminController::class, 'deleteBranch'])->name('branches.delete');
    
    // Калькулятор дверей
    Route::get('/calculator', [App\Http\Controllers\CalculatorController::class, 'index'])->name('calculator.index');
    
    // Настройки
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/preferences', [App\Http\Controllers\SettingsController::class, 'updatePreferences'])->name('settings.preferences');
    Route::put('/settings/system', [App\Http\Controllers\SettingsController::class, 'systemSettings'])->name('settings.system');
    
    // Профиль
    Route::get('/profile', [App\Http\Controllers\SettingsController::class, 'index'])->name('profile.show');
    
    // Workflow маршруты для админов
    Route::post('/contracts/{contract}/submit-to-rop', [ContractWorkflowController::class, 'submitToRop'])->name('contracts.submit-to-rop');
    Route::post('/contracts/{contract}/approve', [ContractWorkflowController::class, 'approve'])->name('contracts.approve');
    Route::post('/contracts/{contract}/reject', [ContractWorkflowController::class, 'reject'])->name('contracts.reject');
    Route::post('/contracts/{contract}/hold', [ContractWorkflowController::class, 'hold'])->name('contracts.hold');
    Route::post('/contracts/{contract}/return', [ContractWorkflowController::class, 'returnForRevision'])->name('contracts.return');
    Route::get('/contracts/{contract}/history', [ContractWorkflowController::class, 'history'])->name('contracts.history');
    
    // Новые workflow маршруты для расширенной воронки
    Route::post('/contracts/{contract}/start-production', [ContractWorkflowController::class, 'startProduction'])->name('contracts.start-production');
    Route::post('/contracts/{contract}/quality-check', [ContractWorkflowController::class, 'qualityCheck'])->name('contracts.quality-check');
    Route::post('/contracts/{contract}/mark-ready', [ContractWorkflowController::class, 'markReady'])->name('contracts.mark-ready');
    Route::post('/contracts/{contract}/ship', [ContractWorkflowController::class, 'ship'])->name('contracts.ship');
    Route::post('/contracts/{contract}/complete', [ContractWorkflowController::class, 'complete'])->name('contracts.complete');
});

// Workflow маршруты для менеджеров
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::post('/contracts/{contract}/submit-to-rop', [ContractWorkflowController::class, 'submitToRop'])->name('contracts.submit-to-rop');
    Route::get('/contracts/{contract}/history', [ContractWorkflowController::class, 'history'])->name('contracts.history');
    
    // Workflow действия для договоров (расширенная воронка)
    Route::post('/contracts/{contract}/start-production', [ContractWorkflowController::class, 'startProduction'])->name('contracts.start-production');
    Route::post('/contracts/{contract}/quality-check', [ContractWorkflowController::class, 'qualityCheck'])->name('contracts.quality-check');
    Route::post('/contracts/{contract}/mark-ready', [ContractWorkflowController::class, 'markReady'])->name('contracts.mark-ready');
    Route::post('/contracts/{contract}/ship', [ContractWorkflowController::class, 'ship'])->name('contracts.ship');
    Route::post('/contracts/{contract}/complete', [ContractWorkflowController::class, 'complete'])->name('contracts.complete');
    
    // Дополнительные workflow действия
    Route::post('/contracts/{contract}/approve', [ContractWorkflowController::class, 'approve'])->name('contracts.approve');
    Route::post('/contracts/{contract}/reject', [ContractWorkflowController::class, 'reject'])->name('contracts.reject');
    Route::post('/contracts/{contract}/hold', [ContractWorkflowController::class, 'hold'])->name('contracts.hold');
    Route::post('/contracts/{contract}/return', [ContractWorkflowController::class, 'returnForRevision'])->name('contracts.return');
});

// Workflow маршруты для РОП
Route::middleware(['auth', 'rop'])->prefix('rop')->name('rop.')->group(function () {
    Route::get('/contracts/{contract}/history', [ContractWorkflowController::class, 'history'])->name('contracts.history');
    
    // Workflow действия для договоров
    Route::post('/contracts/{contract}/approve', [ContractWorkflowController::class, 'approve'])->name('contracts.approve');
    Route::post('/contracts/{contract}/reject', [ContractWorkflowController::class, 'reject'])->name('contracts.reject');
    Route::post('/contracts/{contract}/hold', [ContractWorkflowController::class, 'hold'])->name('contracts.hold');
    Route::post('/contracts/{contract}/return', [ContractWorkflowController::class, 'returnForRevision'])->name('contracts.return');
    Route::post('/contracts/{contract}/start-production', [ContractWorkflowController::class, 'startProduction'])->name('contracts.start-production');
    Route::post('/contracts/{contract}/quality-check', [ContractWorkflowController::class, 'qualityCheck'])->name('contracts.quality-check');
    Route::post('/contracts/{contract}/mark-ready', [ContractWorkflowController::class, 'markReady'])->name('contracts.mark-ready');
    Route::post('/contracts/{contract}/ship', [ContractWorkflowController::class, 'ship'])->name('contracts.ship');
    Route::post('/contracts/{contract}/complete', [ContractWorkflowController::class, 'complete'])->name('contracts.complete');
});

// Роуты директора удалены
