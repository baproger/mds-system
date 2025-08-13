@extends('layouts.admin')

@section('title', 'Новый договор')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
                            <!-- Заголовок -->
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Новый договор</h1>
                            <p class="page-subtitle">Создание нового договора на поставку дверей</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('contracts.store') }}" enctype="multipart/form-data" class="search-form">
                    @csrf
                <form method="POST" action="{{ route('contracts.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Основная информация -->
                    <div class="form-section">
                        <div class="section-header">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <i class="fas fa-info-circle"></i>
                                <span>Основная информация</span>
                            </div>
                        </div>
                            <div class="form-group">
                                <label for="contract_number" class="form-label required">
                                    <i class="fas fa-hashtag"></i> Номер договора
                                </label>
                                <input type="text" class="form-control @error('contract_number') is-invalid @enderror" 
                                       id="contract_number" name="contract_number" value="{{ old('contract_number') }}" required>
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
                                    <small class="form-text">Введите номер договора вручную. Диапазон для филиала "{{ $userBranch->name }}": {{ $range }}</small>
                                @else
                                    <small class="form-text">Введите номер договора вручную</small>
                                @endif
                                @error('contract_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="date" class="form-label required">
                                    <i class="fas fa-calendar"></i> Дата договора
                                </label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="manager" class="form-label required">
                                    <i class="fas fa-user-tie"></i> Менеджер
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
                                    <i class="fas fa-credit-card"></i> Способ оплаты
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
                    </div>

                    <!-- Информация о клиенте -->
                    <div class="form-section">
                        <h5><i class="fas fa-user"></i> Информация о клиенте</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client" class="form-label required">ФИО клиента</label>
                                    <input type="text" class="form-control @error('client') is-invalid @enderror" 
                                           id="client" name="client" value="{{ old('client') }}" required>
                                    @error('client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="iin" class="form-label required">ИИН клиента</label>
                                    <input type="text" class="form-control @error('iin') is-invalid @enderror" 
                                           id="iin" name="iin" value="{{ old('iin') }}" maxlength="12" pattern="\d{12}" required>
                                    @error('iin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label required">Телефон</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone2" class="form-label">Доп. телефон</label>
                                    <input type="text" class="form-control @error('phone2') is-invalid @enderror" 
                                           id="phone2" name="phone2" value="{{ old('phone2') }}">
                                    @error('phone2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                           id="instagram" name="instagram" value="{{ old('instagram') }}">
                                    @error('instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label required">Адрес установки</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Характеристики двери -->
                    <div class="form-section">
                        <h5><i class="fas fa-door-open"></i> Характеристики двери</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label required">Категория</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">— выберите —</option>
                                        @foreach(array_keys($price) as $cat)
                                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="model" class="form-label required">Модель</label>
                                    <select class="form-control @error('model') is-invalid @enderror" 
                                            id="model" name="model" required>
                                        <option value="">Сначала выберите категорию</option>
                                    </select>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="width" class="form-label required">Ширина (мм)</label>
                                    <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                           id="width" name="width" value="{{ old('width') }}" min="850" step="0.01" required>
                                    @error('width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="height" class="form-label required">Высота (мм)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                           id="height" name="height" value="{{ old('height') }}" min="850" step="0.01" required>
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="leaf" class="form-label required">Створка</label>
                                    <select class="form-control @error('leaf') is-invalid @enderror" id="leaf" name="leaf" required>
                                        <option value="">— выберите —</option>
                                        <option value="1" {{ old('leaf') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('leaf') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('leaf') == '3' ? 'selected' : '' }}>3</option>
                                    </select>
                                    @error('leaf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="framugawidth" class="form-label required">Фрамуга боковая</label>
                                    <select class="form-control @error('framugawidth') is-invalid @enderror" id="framugawidth" name="framugawidth" required>
                                        <option value="">— выберите —</option>
                                        <option value="1" {{ old('framugawidth') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('framugawidth') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="-" {{ old('framugawidth') == '-' ? 'selected' : '' }}>–</option>
                                    </select>
                                    @error('framugawidth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="framugaheight" class="form-label required">Фрамуга верхняя</label>
                                    <select class="form-control @error('framugaheight') is-invalid @enderror" id="framugaheight" name="framugaheight" required>
                                        <option value="">— выберите —</option>
                                        <option value="1" {{ old('framugaheight') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="-" {{ old('framugaheight') == '-' ? 'selected' : '' }}>–</option>
                                    </select>
                                    @error('framugaheight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="steel_thickness" class="form-label">Толщина стали (мм)</label>
                                    <input type="number" class="form-control @error('steel_thickness') is-invalid @enderror" 
                                           id="steel_thickness" name="steel_thickness" value="{{ old('steel_thickness') }}" step="0.01">
                                    @error('steel_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="canvas_thickness" class="form-label">Толщина полотна (мм)</label>
                                    <input type="number" class="form-control @error('canvas_thickness') is-invalid @enderror" 
                                           id="canvas_thickness" name="canvas_thickness" value="{{ old('canvas_thickness') }}" step="0.01">
                                    @error('canvas_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="installation" class="form-label">Установка</label>
                                    <input type="number" class="form-control @error('installation') is-invalid @enderror" 
                                           id="installation" name="installation" value="{{ old('installation') }}">
                                    @error('installation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="delivery" class="form-label">Доставка</label>
                                    <input type="text" class="form-control @error('delivery') is-invalid @enderror" 
                                           id="delivery" name="delivery" value="{{ old('delivery') }}">
                                    @error('delivery')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Покрытия и материалы -->
                    <div class="form-section">
                        <h5><i class="fas fa-palette"></i> Покрытия и материалы</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metal_cover" class="form-label">Покрытие металла</label>
                                    <input type="text" class="form-control @error('metal_cover') is-invalid @enderror" 
                                           id="metal_cover" name="metal_cover" value="{{ old('metal_cover') }}">
                                    @error('metal_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metal_cover_color" class="form-label">Цвет покрытия металла</label>
                                    <input type="text" class="form-control @error('metal_cover_color') is-invalid @enderror" 
                                           id="metal_cover_color" name="metal_cover_color" value="{{ old('metal_cover_color') }}">
                                    @error('metal_cover_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="outer_cover" class="form-label required">Покрытие наружной панели</label>
                                    <select class="form-control @error('outer_cover') is-invalid @enderror" 
                                            id="outer_cover" name="outer_cover" required>
                                        <option value="">Сначала выберите категорию и модель</option>
                                    </select>
                                    @error('outer_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="outer_cover_color" class="form-label">Цвет покрытия наружной панели</label>
                                    <input type="text" class="form-control" 
                                           id="outer_cover_color" name="outer_cover_color" 
                                           placeholder="Пример: RAL 9010" value="{{ old('outer_cover_color') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inner_trim" class="form-label required">Внутренняя обшивка</label>
                                    <select class="form-control @error('inner_trim') is-invalid @enderror" 
                                            id="inner_trim" name="inner_trim" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('inner_trim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inner_cover" class="form-label required">Покрытие внутренней обшивки</label>
                                    <select class="form-control @error('inner_cover') is-invalid @enderror" 
                                            id="inner_cover" name="inner_cover" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('inner_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="glass_unit" class="form-label required">Стеклопакет</label>
                                    <select class="form-control @error('glass_unit') is-invalid @enderror" 
                                            id="glass_unit" name="glass_unit" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('glass_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lock" class="form-label required">Замок</label>
                                    <select class="form-control @error('lock') is-invalid @enderror" 
                                            id="lock" name="lock" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('lock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="handle" class="form-label required">Ручка</label>
                                    <input type="text" class="form-control @error('handle') is-invalid @enderror" 
                                           id="handle" name="handle" value="{{ old('handle') }}" required>
                                    @error('handle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительные услуги -->
                    <div class="form-section">
                        <h5><i class="fas fa-tools"></i> Дополнительные услуги</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="measurement" class="form-label">Замер</label>
                                    <input type="text" class="form-control @error('measurement') is-invalid @enderror" 
                                           id="measurement" name="measurement" value="{{ old('measurement') }}">
                                    @error('measurement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="extra" class="form-label">Дополнительные услуги</label>
                                    <input type="text" class="form-control @error('extra') is-invalid @enderror" 
                                           id="extra" name="extra" value="{{ old('extra') }}">
                                    @error('extra')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Финансовая информация -->
                    <div class="form-section">
                        <h5><i class="fas fa-money-bill-wave"></i> Финансовая информация</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_total" class="form-label required">Общая стоимость</label>
                                    <input type="number" class="form-control @error('order_total') is-invalid @enderror" 
                                           id="order_total" name="order_total" value="{{ old('order_total') }}" min="0" step="1" required>
                                    @error('order_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_deposit" class="form-label required">Предоплата</label>
                                    <input type="number" class="form-control @error('order_deposit') is-invalid @enderror" 
                                           id="order_deposit" name="order_deposit" value="{{ old('order_deposit') }}" min="0" step="1" required>
                                    @error('order_deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_remainder" class="form-label required">Остаток предоплаты</label>
                                    <input type="number" class="form-control @error('order_remainder') is-invalid @enderror" 
                                           id="order_remainder" name="order_remainder" value="{{ old('order_remainder') }}" min="0" step="1" required>
                                    @error('order_remainder')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_due" class="form-label required">К оплате после изготовления</label>
                                    <input type="number" class="form-control @error('order_due') is-invalid @enderror" 
                                           id="order_due" name="order_due" value="{{ old('order_due') }}" min="0" step="1" required>
                                    @error('order_due')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Фотографии -->
                    <div class="form-section">
                        <h5><i class="fas fa-camera"></i> Фотографии</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Фото двери</label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Дополнительное фото</label>
                                    <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                                           id="attachment" name="attachment" accept="image/*">
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки действий -->
                    <div class="form-actions">
                        <a href="{{ route('contracts.index') }}" class="btn btn-cancel">
                            <i class="fas fa-arrow-left"></i> Назад
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save"></i> Создать договор
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

<style>
/* ========== БАЗОВЫЙ АДМИН-СТИЛЬ ========== */
body{background:var(--bg-primary)}
.container-fluid{background:var(--bg-primary)}
.page-header{margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid #e5e7eb}
.header-content{display:flex;align-items:center;gap:16px}
.header-icon{width:48px;height:48px;background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
.page-title{font-size:28px;font-weight:700;color:#6b7280;margin:0}
.page-subtitle{font-size:14px;color:#6b7280;margin:4px 0 0 0}

.form-section{background:#fff;border-radius:12px;padding:24px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid #f3f4f6;animation:fadeIn .3s ease-out}
.section-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid #f3f4f6;font-weight:600;font-size:16px;color:#374151}
.section-header i{color:#fff;font-size:18px}
.section-actions{display:flex;gap:12px}

.search-form .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px}
.form-group{position:relative}
.form-label{display:flex;align-items:center;gap:8px;font-weight:600;font-size:14px;color:#374151;margin-bottom:8px}
.form-label i{color:#6b7280;font-size:14px}
.form-label.required:after{content:" *";color:#ef4444}
.form-text{color:#6b7280;font-size:12px}

.form-control{width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:8px;font-size:14px;transition:.2s;background:#fafafa}
.form-control:focus{outline:none;border-color:#1ba4e9;background:#fff;box-shadow:0 0 0 3px rgba(27,164,233,.1)}
.form-control.is-invalid{border-color:#ef4444}
.invalid-feedback{color:#ef4444;font-size:12px;margin-top:4px}

.form-actions{display:flex;gap:12px;flex-wrap:wrap;padding-top:16px}
.btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;border:none;cursor:pointer;transition:.2s}
    .btn i { color: #fff !important; }.btn-sm{padding:8px 12px;font-size:12px}
    .btn i { color: #fff !important; }.btn-cancel{background:#f3f4f6;color:#374151;border:1px solid #d1d5db}
    .btn i { color: #fff !important; }.btn-cancel:hover{background:#e5e7eb;transform:translateY(-1px)}
    .btn i { color: #fff !important; }.btn-save{color:#fff;}
.btn-save i{color:#fff !important;}
.btn-save{background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);color:#fff;box-shadow:0 2px 4px rgba(27,164,233,.2)}
    .btn i { color: #fff !important; }.btn-save:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(27,164,233,.3)}
    .btn i { color: #fff !important; }.btn-danger{background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:#fff;box-shadow:0 2px 4px rgba(239,68,68,.2)}
    .btn i { color: #fff !important; }.btn-danger:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(239,68,68,.3)}
    .btn i { color: #fff !important; }
/* Анимации */
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

/* ========== АДАПТИВ ========== */
@media (max-width:768px){
  .search-form .form-grid,.form-grid{grid-template-columns:1fr}
  .form-actions{flex-direction:column}
  .btn{width:100%;justify-content:center}
    .btn i { color: #fff !important; }}
</style>
@endsection

@push('scripts')
<script>
const price = @json($price);
const fusionModels = @json($fusionModels);

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const modelSelect = document.getElementById('model');
    const outerCoverSelect = document.getElementById('outer_cover');
    const innerTrimSelect = document.getElementById('inner_trim');
    const innerCoverSelect = document.getElementById('inner_cover');
    const glassUnitSelect = document.getElementById('glass_unit');
    const lockSelect = document.getElementById('lock');
    const extraInput = document.getElementById('extra');

    // Новые элементы для автозаполнения толщины
    const steelThicknessInput = document.getElementById('steel_thickness');
    const canvasThicknessInput = document.getElementById('canvas_thickness');

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
        modelSelect.innerHTML = '<option value="">— выберите —</option>';
        if (price[categorySelect.value]) {
            Object.keys(price[categorySelect.value]).forEach(function (m) {
                const opt = document.createElement('option');
                opt.value = m; opt.textContent = m;
                modelSelect.appendChild(opt);
            });
        }
    }

    function updateFields() {
        const category = categorySelect.value;
        const model = modelSelect.value;
        const isFusion = fusionModels.includes(model);

        outerCoverSelect.innerHTML = '';
        const covers = isFusion ? config.fusion_outer_cover : config.default_outer_cover[category];
        if (covers) {
            covers.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v; opt.textContent = v;
                outerCoverSelect.appendChild(opt);
            });
        }

        innerTrimSelect.innerHTML = '<option value="">— выберите —</option>';
        (config.inner_trim[category] || []).forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            innerTrimSelect.appendChild(opt);
        });

        innerCoverSelect.innerHTML = '<option value="">— выберите —</option>';
        (config.inner_cover[category] || []).forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            innerCoverSelect.appendChild(opt);
        });
    }

    function updateGlass() {
        glassUnitSelect.innerHTML = '<option value="">— выберите —</option>';
        (config.glass[categorySelect.value] || []).forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            glassUnitSelect.appendChild(opt);
        });
    }

    function updateLock() {
        lockSelect.innerHTML = '<option value="">— выберите —</option>';
        (config.lock[categorySelect.value] || []).forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            lockSelect.appendChild(opt);
        });
    }

    // Автозаполнение толщины стали и полотна
    function updateThickness() {
        const category = categorySelect.value;
        const model = modelSelect.value;
        // Толщина стали
        const steelMap = { Lux: "1.8", Premium: "1.5", Comfort: "1.4" };
        if (steelThicknessInput) steelThicknessInput.value = steelMap[category] || "1.4";
        // Толщина полотна
        const fusion = fusionModels.includes(model);
        const canvasMap = fusion ? { Lux: 115, Premium: 100, Comfort: 90 } : { Lux: 100, Premium: 90, Comfort: 80 };
        if (canvasThicknessInput) canvasThicknessInput.value = canvasMap[category] || 80;
    }

    function updateExtra() {
        if (!extraInput) return;
        extraInput.value = "";
        if (innerTrimSelect.value.includes('доплату')) extraInput.value += "внутренняя обшивка - шпон (доплата); ";
        if (innerCoverSelect.value.includes('доплату')) extraInput.value += innerCoverSelect.value + "; ";
        if (categorySelect.value === "Premium" && glassUnitSelect.value.includes('калёный')) extraInput.value += "калёный стеклопакет (доплата); ";
    }

    categorySelect.addEventListener('change', () => { 
        updateModels(); 
        updateFields(); 
        updateGlass(); 
        updateLock(); 
        updateThickness();
    });

    modelSelect.addEventListener('change', () => { 
        updateFields(); 
        updateThickness();
    });

    innerTrimSelect.addEventListener('change', updateExtra);
    innerCoverSelect.addEventListener('change', updateExtra);
    glassUnitSelect.addEventListener('change', updateExtra);

    // Инициализация значений при открытии формы
    updateModels();
    updateFields();
    updateGlass();
    updateLock();
    updateThickness();
});
</script>@endpush