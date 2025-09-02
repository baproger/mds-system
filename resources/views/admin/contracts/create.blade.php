@extends('layouts.admin')

@section('title', 'Новый договор')

@section('content')
@if($errors->has('validation'))
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->get('validation') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="edit-branch-container">
    <!-- Заголовок страницы (как в "Добавить филиал") -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-plus"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Новый договор</h1>
                <p class="page-subtitle">Создание нового договора</p>
            </div>
        </div>
    </div>

    <!-- Форма создания договора -->
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-info-circle"></i>
            <span>Основная информация</span>
        </div>

        <form method="POST" action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.store' : (Auth::user()->role === 'manager' ? 'manager.contracts.store' : 'rop.contracts.store')) }}" enctype="multipart/form-data" class="form-content">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="contract_number" class="form-label required">
                        <i class="fas fa-hashtag"></i>
                        Номер договора
                    </label>
                    <input type="text" class="form-control @error('contract_number') is-invalid @enderror"
                           id="contract_number" name="contract_number" value="{{ old('contract_number') }}" placeholder="Например: 25001" required>
                    @if($userBranch)
                        @php
                            $ranges = [
                                'SHY-PP' => '20000-29999',
                                'SHY-RZ' => '30000-39999',
                                'AKT' => '40000-49999',
                                'ALA-TST' => '50000-57999',
                                'ALA-SC' => '58000-59999',
                                'TRZ' => '100000-119999',
                                'ATR' => '120000-139999',
                                'TAS' => '60000-69999',
                            ];
                            $range = $ranges[$userBranch->code] ?? 'неизвестен';
                        @endphp
                        <small class="form-text">Диапазон для филиала "{{ $userBranch->name }}": {{ $range }}</small>
                    @endif
                    @error('contract_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date" class="form-label required">
                        <i class="fas fa-calendar-alt"></i>
                        Дата договора
                    </label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                           id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="manager" class="form-label required">
                        <i class="fas fa-user-tie"></i>
                        Менеджер
                    </label>
                    <select class="form-control @error('manager') is-invalid @enderror"
                            id="manager" name="manager" required>
                        <option value="">— выберите —</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager }}" {{ old('manager') == $manager ? 'selected' : '' }}>
                                {{ $manager }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment" class="form-label">
                        <i class="fas fa-wallet"></i>
                        Способ оплаты
                    </label>
                    <select class="form-control @error('payment') is-invalid @enderror"
                            id="payment" name="payment">
                        <option value="Наличный" {{ old('payment', 'Наличный') == 'Наличный' ? 'selected' : '' }}>Наличный</option>
                        <option value="Безналичный" {{ old('payment') == 'Безналичный' ? 'selected' : '' }}>Безналичный</option>
                        <option value="Рассрочка" {{ old('payment') == 'Рассрочка' ? 'selected' : '' }}>Рассрочка</option>
                    </select>
                    @error('payment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(Auth::user()->role === 'admin')
                <div class="form-group">
                    <label for="branch_id" class="form-label required">
                        <i class="fas fa-building"></i>
                        Филиал
                    </label>
                    <select class="form-control @error('branch_id') is-invalid @enderror"
                            id="branch_id" name="branch_id" required>
                        <option value="">— выберите филиал —</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $userBranch->id ?? '') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif
            </div>

            <!-- Информация о клиенте -->
            <div class="section-header">
                <i class="fas fa-user"></i>
                <span>Информация о клиенте</span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="client" class="form-label required"><i class="fas fa-id-card"></i> ФИО клиента</label>
                    <input type="text" class="form-control @error('client') is-invalid @enderror"
                           id="client" name="client" value="{{ old('client') }}" placeholder="ФИО полностью" required>
                    @error('client') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="instagram" class="form-label required"><i class="fab fa-instagram"></i> Instagram</label>
                    <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                           id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="@username" required>
                    @error('instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="iin" class="form-label required"><i class="fas fa-barcode"></i> ИИН</label>
                    <input type="text" class="form-control @error('iin') is-invalid @enderror"
                           id="iin" name="iin" value="{{ old('iin') }}" maxlength="12" placeholder="12 цифр" required>
                    @error('iin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label required"><i class="fas fa-phone"></i> Телефон</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" value="{{ old('phone') }}" placeholder="+7 777 123 45 67" required>
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="phone2" class="form-label required"><i class="fas fa-phone-alt"></i> Дополнительный телефон</label>
                    <input type="tel" class="form-control @error('phone2') is-invalid @enderror"
                           id="phone2" name="phone2" value="{{ old('phone2') }}" placeholder="+7 700 000 00 00" required>
                    @error('phone2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i> Адрес установки</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address" name="address" rows="3" placeholder="Город, улица, дом, квартира">{{ old('address', '') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Характеристики двери -->
            <div class="section-header">
                <i class="fas fa-door-open"></i>
                <span>Характеристики двери</span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="category" class="form-label required"><i class="fas fa-tags"></i> Категория</label>
                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                        <option value="">— выберите —</option>
                        <option value="Lux" {{ old('category') == 'Lux' ? 'selected' : '' }}>Lux</option>
                        <option value="Premium" {{ old('category') == 'Premium' ? 'selected' : '' }}>Premium</option>
                        <option value="Comfort" {{ old('category') == 'Comfort' ? 'selected' : '' }}>Comfort</option>
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="model" class="form-label required"><i class="fas fa-box"></i> Модель</label>
                    <select class="form-control @error('model') is-invalid @enderror" id="model" name="model" required>
                        <option value="">— выберите —</option>
                    </select>
                    @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="width" class="form-label required"><i class="fas fa-arrows-alt-h"></i> Ширина (мм)</label>
                    <input type="number" class="form-control @error('width') is-invalid @enderror"
                           id="width" name="width" value="{{ old('width', '') }}" min="850" placeholder="Например: 900" required>
                    @error('width') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="height" class="form-label required"><i class="fas fa-arrows-alt-v"></i> Высота (мм)</label>
                    <input type="number" class="form-control @error('height') is-invalid @enderror"
                           id="height" name="height" value="{{ old('height', '') }}" min="850" placeholder="Например: 2050" required>
                    @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="design" class="form-label"><i class="fas fa-palette"></i> Дизайн</label>
                    <select class="form-control @error('design') is-invalid @enderror" id="design" name="design">
                        <option value="Меняется" {{ old('design', 'Меняется') == 'Меняется' ? 'selected' : '' }}>Меняется</option>
                        <option value="Не меняется" {{ old('design') == 'Не меняется' ? 'selected' : '' }}>Не меняется</option>
                    </select>
                    @error('design') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="leaf" class="form-label required"><i class="fas fa-columns"></i> Створка</label>
                    <select class="form-control @error('leaf') is-invalid @enderror" id="leaf" name="leaf" required>
                        <option value="">— выберите —</option>
                        <option value="1" {{ old('leaf') == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ old('leaf') == '2' ? 'selected' : '' }}>2</option>
                        <option value="3" {{ old('leaf') == '3' ? 'selected' : '' }}>3</option>
                    </select>
                    @error('leaf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="framugawidth" class="form-label required"><i class="fas fa-border-style"></i> Фрамуга боковая</label>
                    <select class="form-control @error('framugawidth') is-invalid @enderror" id="framugawidth" name="framugawidth" required>
                        <option value="">— выберите —</option>
                        <option value="1" {{ old('framugawidth') == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ old('framugawidth') == '2' ? 'selected' : '' }}>2</option>
                        <option value="-" {{ old('framugawidth') == '-' ? 'selected' : '' }}>–</option>
                    </select>
                    @error('framugawidth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="framugaheight" class="form-label required"><i class="fas fa-border-top"></i> Фрамуга верхняя</label>
                    <select class="form-control @error('framugaheight') is-invalid @enderror" id="framugaheight" name="framugaheight" required>
                        <option value="">— выберите —</option>
                        <option value="1" {{ old('framugaheight') == '1' ? 'selected' : '' }}>1</option>
                        <option value="-" {{ old('framugaheight') == '-' ? 'selected' : '' }}>–</option>
                    </select>
                    @error('framugaheight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="forging" class="form-label"><i class="fas fa-bolt"></i> Ковка</label>
                    <select class="form-control @error('forging') is-invalid @enderror" id="forging" name="forging">
                        <option value="">— выберите —</option>
                        <option value="Внутри стеклопакета" {{ old('forging') == 'Внутри стеклопакета' ? 'selected' : '' }}>Внутри стеклопакета</option>
                        <option value="Снаружи стеклопакета" {{ old('forging') == 'Снаружи стеклопакета' ? 'selected' : '' }}>Снаружи стеклопакета</option>
                        <option value="-" {{ old('forging') == '-' ? 'selected' : '' }}>–</option>
                    </select>
                    @error('forging') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="opening" class="form-label"><i class="fas fa-exchange-alt"></i> Открывание</label>
                    <select class="form-control @error('opening') is-invalid @enderror" id="opening" name="opening">
                        <option value="Правое" {{ old('opening', 'Правое') == 'Правое' ? 'selected' : '' }}>Правое</option>
                        <option value="Левое"  {{ old('opening') == 'Левое' ? 'selected' : '' }}>Левое</option>
                    </select>
                    @error('opening') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="frame" class="form-label"><i class="fas fa-border-all"></i> Обналичники</label>
                    <select class="form-control @error('frame') is-invalid @enderror" id="frame" name="frame">
                        <option value="Стандарт"   {{ old('frame', 'Стандарт') == 'Стандарт' ? 'selected' : '' }}>Стандарт</option>
                        <option value="Стандарт 2" {{ old('frame') == 'Стандарт 2' ? 'selected' : '' }}>Стандарт 2</option>
                        <option value="Нестандарт" {{ old('frame') == 'Нестандарт' ? 'selected' : '' }}>Нестандарт</option>
                    </select>
                    @error('frame') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Дополнительные характеристики -->
            <div class="section-header">
                <i class="fas fa-cogs"></i>
                <span>Дополнительные характеристики</span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="outer_panel" class="form-label"><i class="fas fa-layer-group"></i> Наружная панель</label>
                    <input type="text" class="form-control @error('outer_panel') is-invalid @enderror"
                           id="outer_panel" name="outer_panel" value="{{ old('outer_panel', '') }}" readonly>
                    @error('outer_panel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="outer_cover" class="form-label required"><i class="fas fa-brush"></i> Покрытие наружной панели</label>
                    <select class="form-control @error('outer_cover') is-invalid @enderror" id="outer_cover" name="outer_cover" required>
                        <option value="">— выберите —</option>
                    </select>
                    @error('outer_cover') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="outer_cover_color" class="form-label"><i class="fas fa-fill-drip"></i> Цвет покрытия наружной панели</label>
                    <input type="text" class="form-control @error('outer_cover_color') is-invalid @enderror"
                           id="outer_cover_color" name="outer_cover_color" value="{{ old('outer_cover_color', '') }}" placeholder="Пример: RAL 9010">
                    @error('outer_cover_color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="metal_cover_hidden" class="form-label"><i class="fas fa-industry"></i> Покрытие металла</label>
                    <input type="text" class="form-control @error('metal_cover_hidden') is-invalid @enderror"
                           id="metal_cover_hidden" name="metal_cover_hidden" value="Порошково-полимерное" readonly>
                    @error('metal_cover_hidden') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="metal_cover_color" class="form-label"><i class="fas fa-fill"></i> Цвет покрытия металла</label>
                    <input type="text" class="form-control @error('metal_cover_color') is-invalid @enderror"
                           id="metal_cover_color" name="metal_cover_color" value="{{ old('metal_cover_color', '') }}" placeholder="Пример: RAL 9010">
                    @error('metal_cover_color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="inner_trim" class="form-label required"><i class="fas fa-th"></i> Внутренняя обшивка</label>
                    <select class="form-control @error('inner_trim') is-invalid @enderror" id="inner_trim" name="inner_trim" required>
                        <option value="">— выберите —</option>
                    </select>
                    @error('inner_trim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="inner_cover" class="form-label required"><i class="fas fa-paint-roller"></i> Покрытие внутренней обшивки</label>
                    <select class="form-control @error('inner_cover') is-invalid @enderror" id="inner_cover" name="inner_cover" required>
                        <option value="">— выберите —</option>
                    </select>
                    @error('inner_cover') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="inner_trim_color" class="form-label"><i class="fas fa-highlighter"></i> Цвет покрытия внутренней обшивки</label>
                    <input type="text" class="form-control @error('inner_trim_color') is-invalid @enderror"
                           id="inner_trim_color" name="inner_trim_color" value="{{ old('inner_trim_color', '') }}" placeholder="Пример: RAL 9010">
                    @error('inner_trim_color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="glass_unit" class="form-label required"><i class="fas fa-window-maximize"></i> Стеклопакет</label>
                    <select class="form-control @error('glass_unit') is-invalid @enderror" id="glass_unit" name="glass_unit" required>
                        <option value="">— выберите —</option>
                    </select>
                    @error('glass_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="lock" class="form-label required"><i class="fas fa-key"></i> Замок</label>
                    <select class="form-control @error('lock') is-invalid @enderror" id="lock" name="lock" required>
                        <option value="">— выберите —</option>
                    </select>
                    @error('lock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="handle" class="form-label required"><i class="fas fa-grip-horizontal"></i> Ручка</label>
                    <input type="text" class="form-control @error('handle') is-invalid @enderror"
                           id="handle" name="handle" value="{{ old('handle', '') }}" placeholder="Модель ручки" required>
                    @error('handle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="steel_thickness" class="form-label"><i class="fas fa-weight-hanging"></i> Толщина стали (мм)</label>
                    <input type="text" class="form-control @error('steel_thickness') is-invalid @enderror"
                           id="steel_thickness" name="steel_thickness" value="{{ old('steel_thickness', '') }}" readonly>
                    @error('steel_thickness') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="canvas_thickness" class="form-label"><i class="fas fa-ruler-combined"></i> Толщина полотна (мм)</label>
                    <input type="text" class="form-control @error('canvas_thickness') is-invalid @enderror"
                           id="canvas_thickness" name="canvas_thickness" value="{{ old('canvas_thickness', '') }}" readonly>
                    @error('canvas_thickness') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="extra" class="form-label"><i class="fas fa-plus-circle"></i> Дополнительно</label>
                    <input type="text" class="form-control @error('extra') is-invalid @enderror"
                           id="extra" name="extra" value="{{ old('extra', '') }}">
                    @error('extra') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Услуги -->
            <div class="section-header">
                <i class="fas fa-tools"></i>
                <span>Услуги</span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="measurement" class="form-label"><i class="fas fa-ruler"></i> Замер</label>
                    <select class="form-control @error('measurement') is-invalid @enderror" id="measurement" name="measurement">
                        <option value="online"  {{ old('measurement', 'online') == 'online' ? 'selected' : '' }}>онлайн</option>
                        <option value="offline" {{ old('measurement') == 'offline' ? 'selected' : '' }}>оффлайн</option>
                    </select>
                    @error('measurement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="delivery" class="form-label"><i class="fas fa-truck"></i> Доставка</label>
                    <select class="form-control @error('delivery') is-invalid @enderror" id="delivery" name="delivery">
                        <option value="+" {{ old('delivery', '+') == '+' ? 'selected' : '' }}>+</option>
                        <option value="-" {{ old('delivery') == '-' ? 'selected' : '' }}>-</option>
                    </select>
                    @error('delivery') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="installation" class="form-label"><i class="fas fa-tools"></i> Установка</label>
                    <input type="text" class="form-control @error('installation') is-invalid @enderror"
                           id="installation" name="installation" value="{{ old('installation', '') }}" placeholder="Сумма в ₸">
                    @error('installation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Стоимость -->
            <div class="section-header">
                <i class="fas fa-money-bill-wave"></i>
                <span>Стоимость</span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="order_total" class="form-label required"><i class="fas fa-sum"></i> Общая сумма</label>
                    <input type="number" class="form-control @error('order_total') is-invalid @enderror"
                           id="order_total" name="order_total" value="{{ old('order_total', '') }}" min="0" placeholder="Сумма в ₸" required>
                    @error('order_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="order_deposit" class="form-label required"><i class="fas fa-hand-holding-usd"></i> Предоплата</label>
                    <input type="number" class="form-control @error('order_deposit') is-invalid @enderror"
                           id="order_deposit" name="order_deposit" value="{{ old('order_deposit', '') }}" min="0" placeholder="Сумма в ₸" required>
                    @error('order_deposit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="order_remainder" class="form-label"><i class="fas fa-wallet"></i> Остаток</label>
                    <input type="number" class="form-control @error('order_remainder') is-invalid @enderror"
                           id="order_remainder" name="order_remainder" value="{{ old('order_remainder', '') }}" min="0" placeholder="Сумма в ₸">
                    @error('order_remainder') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="order_due" class="form-label required"><i class="fas fa-cash-register"></i> К оплате</label>
                    <input type="number" class="form-control @error('order_due') is-invalid @enderror"
                           id="order_due" name="order_due" value="{{ old('order_due', '') }}" min="0" placeholder="Сумма в ₸" required>
                    @error('order_due') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Файлы -->
            <div class="section-header">
                <i class="fas fa-file-upload"></i>
                <span>Файлы</span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="photo" class="form-label"><i class="fas fa-image"></i> Фото двери</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                           id="photo" name="photo" accept="image/*">
                    <small class="form-text">Допустимые форматы: JPG, PNG, GIF</small>
                    @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="attachment" class="form-label"><i class="fas fa-images"></i> Дополнительные фото</label>
                    <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                           id="attachment" name="attachment" accept="image/*">
                    <small class="form-text">Допустимые форматы: JPG, PNG, GIF</small>
                    @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Действия -->
            <div class="form-actions">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i>
                    Создать договор
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

<style>
.edit-branch-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.header-content { 
    display: flex; 
    align-items: center; 
    gap: 16px; 
}

.header-icon {
    width: 48px; 
    height: 48px;
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    border-radius: 12px; 
    display: flex; 
    align-items: center; 
    justify-content: center;
    color: #fff; 
    font-size: 20px;
}

.edit-branch-container .page-title { 
    font-size: 28px !important; 
    font-weight: 700; 
    color: #616876 !important; 
    margin: 0; 
    background: none !important;
    -webkit-background-clip: unset !important;
    -webkit-text-fill-color: unset !important;
    background-clip: unset !important;
}

.page-subtitle { 
    font-size: 14px; 
    color: #6b7280; 
    margin: 4px 0 0 0; 
}

.form-section {
    background: #fff; 
    border-radius: 12px; 
    padding: 24px; 
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,.1); 
    border: 1px solid #f3f4f6;
}

.section-header {
    display: flex; 
    align-items: center; 
    gap: 12px;
    margin: 24px 0; 
    padding-bottom: 16px; 
    border-bottom: 2px solid #f3f4f6;
    font-weight: 600; 
    font-size: 16px; 
    color: #374151;
}

.section-header i { 
    color:rgb(255, 255, 255); 
    font-size: 18px; 
}

.form-content { 
    display: flex; 
    flex-direction: column; 
    gap: 24px; 
}

.form-grid {
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.form-group { 
    position: relative; 
}

.form-label {
    display: flex; 
    align-items: center; 
    gap: 8px;
    font-weight: 600; 
    font-size: 14px; 
    color: #374151; 
    margin-bottom: 8px;
}

.form-label i { 
    color: #5a8fe6; 
    font-size: 14px; 
}

.form-label.required::after { 
    content: " *"; 
    color: #ef4444; 
}

.form-control {
    width: 100%; 
    padding: 12px 16px; 
    border: 2px solid #e5e7eb; 
    border-radius: 8px;
    font-size: 14px; 
    transition: .2s; 
    background: #fafafa;
}

.form-control:focus {
    outline: none; 
    border-color: #1ba4e9; 
    background: #fff;
    box-shadow: 0 0 0 3px rgba(27,164,233,.1);
}

.form-control.is-invalid { 
    border-color: #ef4444; 
    box-shadow: 0 0 0 3px rgba(239,68,68,.1); 
}

.invalid-feedback { 
    color: #ef4444; 
    font-size: 12px; 
    margin-top: 4px; 
}

.form-text { 
    color: #6b7280; 
    font-size: 12px; 
    margin-top: 4px; 
}

.form-actions {
    display: flex; 
    justify-content: flex-end; 
    gap: 12px;
    margin-top: 24px; 
    padding-top: 24px; 
    border-top: 1px solid #f3f4f6;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(27, 164, 233, 0.3);
}

@media (max-width: 768px) {
    .edit-branch-container { 
        padding: 16px; 
    }
    
    .form-grid { 
        grid-template-columns: 1fr; 
        gap: 12px; 
    }
    
    .form-actions { 
        flex-direction: column; 
    }
    
    .form-actions .btn { 
        width: 100%; 
        justify-content: center; 
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const price = @json($price);
    const fusionModels = @json($fusionModels);

    const cat = document.getElementById('category');
    const mdl = document.getElementById('model');
    const outerPanel = document.getElementById('outer_panel');
    const outerCover = document.getElementById('outer_cover');
    const innerTrim = document.getElementById('inner_trim');
    const innerCover = document.getElementById('inner_cover');
    const extra = document.getElementById('extra');
    const glassUnit = document.getElementById('glass_unit');
    const lock = document.getElementById('lock');
    const steelThickness = document.getElementById('steel_thickness');
    const canvasThickness = document.getElementById('canvas_thickness');

    const config = {
        outer_panel: { Lux: "Оцинкованная сталь", Premium: "Оцинкованная сталь", Comfort: "Холоднокатанная сталь" },
        fusion_outer_cover: ["МДФ-эмаль", "Шпон-марилька"],
        default_outer_cover: {
            Lux: ["Оцинкованная сталь"],
            Premium: ["Оцинкованная сталь"],
            Comfort: ["Холоднокатанная сталь"]
        },
        inner_trim: {
            Lux: ["МДФ", "металл", "шпон"],
            Premium: ["МДФ", "металл", "шпон (за доплату)"],
            Comfort: ["МДФ", "металл"]
        },
        inner_cover: {
            Lux: ["Марилька", "Эмаль", "ПВХ пленка"],
            Premium: ["Эмаль", "ПВХ пленка", "Шпон-марилька (за доплату)"],
            Comfort: ["металл", "Эмаль (за доплату)"]
        },
        glass: {
            Lux: ["синий зеркальный (калёный)", "йодовый зеркальный (калёный)", "йодовый (калёный)", "прозрачный (калёный)"],
            Premium: ["синий зеркальный (некалёный)", "йодовый зеркальный (некалёный)", "йодовый (некалёный)", "прозрачный (некалёный)", "синий зеркальный (калёный)", "йодовый зеркальный (калёный)", "йодовый (калёный)", "прозрачный (калёный)"],
            Comfort: ["синий зеркальный (некалёный)", "йодовый зеркальный (некалёный)", "йодовый (некалёный)", "прозрачный (некалёный)"]
        },
        lock: { Lux: ["Kale", "Guardian"], Premium: ["Kale", "Guardian", "Galeon"], Comfort: ["Argus", "Galeon"] }
    };

    function updateModels() {
        mdl.innerHTML = '<option value="">— выберите —</option>';
        if (price[cat.value]) {
            Object.keys(price[cat.value]).forEach(function (m) {
                const opt = document.createElement('option');
                opt.value = m; opt.textContent = m;
                mdl.appendChild(opt);
            });
        }
        if (window.modelOld) mdl.value = window.modelOld;
    }

    function updateFields() {
        const category = cat.value;
        const model = mdl.value;
        const isFusion = fusionModels.includes(model);

        if (outerPanel) {
            outerPanel.value = isFusion ? "МДФ" : config.outer_panel[category];
        }

        if (outerCover) {
            outerCover.innerHTML = '';
            const covers = isFusion ? config.fusion_outer_cover : config.default_outer_cover[category];
            if (covers) {
                covers.forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v; opt.textContent = v;
                    outerCover.appendChild(opt);
                });
            }
        }

        if (innerTrim) {
            innerTrim.innerHTML = '<option value="">— выберите —</option>';
            (config.inner_trim[category] || []).forEach(v => {
                const opt = document.createElement('option');
                opt.value = v; opt.textContent = v;
                innerTrim.appendChild(opt);
            });
        }

        if (innerCover) {
            innerCover.innerHTML = '<option value="">— выберите —</option>';
            (config.inner_cover[category] || []).forEach(v => {
                const opt = document.createElement('option');
                opt.value = v; opt.textContent = v;
                innerCover.appendChild(opt);
            });
        }
    }

    function updateGlass() {
        if (glassUnit) {
            glassUnit.innerHTML = '<option value="">— выберите —</option>';
            (config.glass[cat.value] || []).forEach(v => {
                const opt = document.createElement('option');
                opt.value = v; opt.textContent = v;
                glassUnit.appendChild(opt);
            });
        }
    }

    function updateLock() {
        if (lock) {
            lock.innerHTML = '<option value="">— выберите —</option>';
            (config.lock[cat.value] || []).forEach(v => {
                const opt = document.createElement('option');
                opt.value = v; opt.textContent = v;
                lock.appendChild(opt);
            });
        }
    }

    function updateThickness() {
        let base = { Lux: 110, Premium: 90, Comfort: 80 }[cat.value];
        const steel = { Lux: "1.8", Premium: "1.5", Comfort: "1.4" }[cat.value];
        if (fusionModels.includes(mdl.value)) base += 10;

        if (canvasThickness) canvasThickness.value = base || '';
        if (steelThickness) steelThickness.value = steel || '';
    }

    function updateExtra() {
        if (extra) {
            extra.value = "";
            if (innerTrim && innerTrim.value.includes('доплату')) extra.value += "внутренняя обшивка - шпон (доплата); ";
            if (innerCover && innerCover.value.includes('доплату')) extra.value += innerCover.value + "; ";
            if (cat.value === "Premium" && glassUnit && glassUnit.value.includes('калёный')) extra.value += "калёный стеклопакет (доплата); ";
        }
    }

    if (cat) cat.addEventListener('change', () => { updateModels(); updateFields(); updateGlass(); updateLock(); updateThickness(); });
    if (mdl) mdl.addEventListener('change', () => { updateFields(); updateThickness(); });
    if (innerTrim) innerTrim.addEventListener('change', updateExtra);
    if (innerCover) innerCover.addEventListener('change', updateExtra);
    if (glassUnit) glassUnit.addEventListener('change', updateExtra);

    // Инициализация
    if (cat && mdl) {
        updateModels();
        updateFields();
        updateGlass();
        updateLock();
        updateThickness();
    }
});
</script>