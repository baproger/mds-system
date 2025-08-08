# Система управления договорами на Laravel

## 🚀 Что было реализовано

### ✅ Авторизация менеджеров
- Регистрация и вход пользователей
- Привязка пользователей к филиалам
- Защищенные маршруты

### ✅ Ручной ввод номеров договоров
- Менеджеры вводят номера договоров вручную
- Валидация диапазонов номеров по филиалам
- Подсказки с допустимыми диапазонами в формах

### ✅ Диапазоны номеров договоров по филиалам
- **Шымкент Прайм парк** (SHY-PP): 20000-29999
- **Шымкент Ремзона** (SHY-RZ): 30000-39999
- **Актобе** (AKT): 40000-49999
- **Алматы Тастак** (ALA-TST): 50000-57999
- **Алматы СтройСити** (ALA-SC): 58000-59999
- **Тараз** (TRZ): 100000-119999
- **Атырау** (ATR): 120000-139999
- **Ташкент** (TAS): 60000-69999

### ✅ Автоматическая подстановка филиала и менеджера
- При создании договора автоматически подставляется филиал менеджера
- Менеджер автоматически привязывается к договору

### ✅ Сохранение договоров в JSON-файлы
- Все данные договора сохраняются в базе данных
- Дополнительно сохраняются в JSON формате

### ✅ Поиск по договорам
- Поиск по номеру договора
- Поиск по имени клиента
- Поиск по телефону

### ✅ Разделение договоров по менеджерам
- Менеджеры видят только свои договоры
- Администраторы видят все договоры

## 📋 Пошаговая инструкция создания проекта

### 1. Создание Laravel проекта
```bash
composer create-project laravel/laravel door-contracts
cd door-contracts
```

### 2. Создание моделей и миграций
```bash
php artisan make:model Branch -m
php artisan make:model Contract -m
php artisan make:migration add_branch_id_to_users_table --table=users
```

### 3. Настройка миграций

#### Миграция для филиалов (database/migrations/2025_08_04_061208_create_branches_table.php):
```php
Schema::create('branches', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->integer('contract_counter')->default(1);
    $table->timestamps();
});
```

#### Миграция для договоров (database/migrations/2025_08_04_061211_create_contracts_table.php):
```php
Schema::create('contracts', function (Blueprint $table) {
    $table->id();
    $table->string('contract_number')->unique();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('branch_id')->constrained()->onDelete('cascade');
    // ... остальные поля
});
```

#### Миграция для добавления branch_id к пользователям:
```php
Schema::table('users', function (Blueprint $table) {
    $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
    $table->string('role')->default('manager');
});
```

### 4. Настройка моделей

#### Branch.php:
```php
protected $fillable = ['name', 'code', 'contract_counter'];

public function users()
{
    return $this->hasMany(User::class);
}

public function contracts()
{
    return $this->hasMany(Contract::class);
}
```

#### User.php:
```php
protected $fillable = ['name', 'email', 'password', 'branch_id', 'role'];

public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function contracts()
{
    return $this->hasMany(Contract::class);
}
```

#### Contract.php:
```php
protected $fillable = [
    'contract_number', 'user_id', 'branch_id', 'client', 'instagram',
    'iin', 'phone', 'phone2', 'address', 'payment', 'date',
    // ... остальные поля
];

public function user()
{
    return $this->belongsTo(User::class);
}

public function branch()
{
    return $this->belongsTo(Branch::class);
}
```

### 5. Создание контроллеров
```bash
php artisan make:controller AuthController
php artisan make:controller ContractController --resource
```

### 6. Настройка маршрутов (routes/web.php)
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
});
```

### 7. Создание сидеров
```bash
php artisan make:seeder BranchSeeder
php artisan make:seeder UserSeeder
```

### 8. Запуск миграций и сидеров
```bash
php artisan migrate
php artisan db:seed
```

### 9. Настройка аутентификации
```bash
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev
```

## 🔧 Использование системы

### Создание договора
1. Войдите в систему как менеджер
2. Перейдите на страницу "Новый договор"
3. Введите номер договора в соответствии с диапазоном вашего филиала
4. Заполните остальные поля
5. Сохраните договор

### Валидация номеров договоров
Система автоматически проверяет, что номер договора:
- Соответствует диапазону филиала менеджера
- Уникален в системе
- Не пустой

### Диапазоны номеров
Каждый филиал имеет свой диапазон номеров договоров:
- Шымкент Прайм парк: 20000-29999
- Шымкент Ремзона: 30000-39999
- Актобе: 40000-49999
- Алматы Тастак: 50000-57999
- Алматы СтройСити: 58000-59999
- Тараз: 100000-119999
- Атырау: 120000-139999
- Ташкент: 60000-69999

## 🚀 Запуск проекта
```bash
php artisan serve
```

Откройте браузер и перейдите по адресу: http://localhost:8000
