<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminManagerController;
use App\Http\Controllers\ManagerController;
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
    return redirect()->route("contracts.index");
});

// Тестовый маршрут для проверки стилей
Route::get("/test-auth", function () {
    return view("auth.test");
})->name("test.auth");

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
    
    // Маршруты для профиля пользователя
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit/{id?}', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id?}', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
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
    // Профиль
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    // Договоры: список/просмотр/редактирование своих
    Route::get('/contracts', [App\Http\Controllers\AdminController::class, 'contracts'])->name('contracts.index');
    Route::get('/contracts/create', [App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.delete');
    
    // Калькулятор дверей
    Route::get('/calculator', [App\Http\Controllers\CalculatorController::class, 'index'])->name('calculator.index');
    

    
    // Тестовый маршрут
    Route::get('/test', function() {
        return 'Manager test route works! User: ' . Auth::user()->name . ' Role: ' . Auth::user()->role;
    })->name('test');
    
    // Тестовый маршрут для контроллера
    Route::get('/test-controller', function() {
        return 'Manager controller test works!';
    })->name('test-controller');
    
    // Тестовый маршрут для ContractController
    Route::get('/test-contract', [App\Http\Controllers\ContractController::class, 'create'])->name('test-contract');
});

// Роуты для РОП (префикс /rop)
Route::middleware(['auth', 'rop'])->prefix('rop')->name('rop.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    // Профиль
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    // Договоры: список/просмотр/редактирование по филиалу
    Route::get('/contracts', [App\Http\Controllers\AdminController::class, 'contracts'])->name('contracts.index');
    Route::get('/contracts/create', [App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.delete');
    
    // Калькулятор дверей
    Route::get('/calculator', [App\Http\Controllers\CalculatorController::class, 'index'])->name('calculator.index');
});

// Админские маршруты (только для admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Админский дашборд (только для admin)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Админские маршруты профиля
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit/{id?}', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id?}', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Админские страницы договоров
    Route::get('/contracts', [App\Http\Controllers\AdminController::class, 'contracts'])->name('contracts.index');
    Route::get('/contracts/create', [App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.delete');
    
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
});
