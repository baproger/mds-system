@extends('layouts.admin')

@section('title', 'Редактировать договор')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Редактировать договор №{{ $contract->contract_number }}</h1>
                            <p class="page-subtitle">
                                @if(Auth::user()->role === 'rop')
                                    Просмотр и редактирование договора менеджера
                                @elseif(Auth::user()->role === 'manager')
                                    Редактирование вашего договора
                                @else
                                    Редактирование договора
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <form method="POST" action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.update' : (Auth::user()->role === 'manager' ? 'manager.contracts.update' : 'rop.contracts.update'), $contract) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Основная информация -->
                        <div class="section-header">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <i class="fas fa-info-circle"></i>
                                <span>Основная информация</span>
                            </div>
                        </div>

                        <!-- Информация о создании договора -->
                        <div class="info-grid" style="margin-bottom: 24px; padding: 16px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                            @if(Auth::user()->role === 'rop')
                                <div class="info-item" style="grid-column: 1 / -1; background: #fff3cd; padding: 12px; border-radius: 6px; border: 1px solid #ffeaa7; color: #856404;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Внимание:</strong> Вы редактируете договор, созданный менеджером. Все изменения будут сохранены с указанием вашего имени.
                                </div>
                            @endif
                            <div class="info-item">
                                <strong>Создан:</strong> {{ $contract->created_at ? $contract->created_at->format('d.m.Y H:i') : 'Не указано' }}
                            </div>
                            <div class="info-item">
                                <strong>Создатель:</strong> {{ $contract->user ? $contract->user->name : 'Не указано' }}
                            </div>
                            <div class="info-item">
                                <strong>Последнее обновление:</strong> {{ $contract->updated_at ? $contract->updated_at->format('d.m.Y H:i') : 'Не указано' }}
                            </div>
                            <div class="info-item">
                                <strong>Филиал:</strong> {{ $contract->branch ? $contract->branch->name : 'Не указано' }}
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="contract_number" class="form-label required">
                                    <i class="fas fa-hashtag"></i> Номер договора
                                </label>
                                <input type="text" class="form-control @error('contract_number') is-invalid @enderror" 
                                       id="contract_number" name="contract_number" value="{{ old('contract_number', $contract->contract_number) }}" required>
                                @if($contract->branch)
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
                                        $range = $ranges[$contract->branch->code] ?? 'неизвестен';
                                    @endphp
                                    <small class="form-text text-muted">Диапазон для филиала "{{ $contract->branch->name }}": {{ $range }}</small>
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
                                       id="date" name="date" value="{{ old('date', $contract->date->format('Y-m-d')) }}" required>
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
                                        <option value="{{ $manager }}" {{ old('manager', $contract->manager) == $manager ? 'selected' : '' }}>
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
                                    <option value="Наличный" {{ old('payment', $contract->payment) == 'Наличный' ? 'selected' : '' }}>Наличный</option>
                                    <option value="Безналичный" {{ old('payment', $contract->payment) == 'Безналичный' ? 'selected' : '' }}>Безналичный</option>
                                    <option value="Рассрочка" {{ old('payment', $contract->payment) == 'Рассрочка' ? 'selected' : '' }}>Рассрочка</option>
                                </select>
                                @error('payment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Информация о клиенте -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-user"></i>
                            <span>Информация о клиенте</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="client" class="form-label required">ФИО клиента</label>
                                <input type="text" class="form-control @error('client') is-invalid @enderror" 
                                       id="client" name="client" value="{{ old('client', $contract->client) }}" required>
                                @error('client')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="iin" class="form-label required">ИИН клиента</label>
                                <input type="text" class="form-control @error('iin') is-invalid @enderror" 
                                       id="iin" name="iin" value="{{ old('iin', $contract->iin) }}" maxlength="12" pattern="\d{12}" required>
                                @error('iin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label required">Телефон</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $contract->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone2" class="form-label">Доп. телефон</label>
                                <input type="text" class="form-control @error('phone2') is-invalid @enderror" 
                                       id="phone2" name="phone2" value="{{ old('phone2', $contract->phone2) }}">
                                @error('phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                       id="instagram" name="instagram" value="{{ old('instagram', $contract->instagram) }}">
                                @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address" class="form-label required">Адрес установки</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" required>{{ old('address', $contract->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Характеристики двери -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-door-open"></i>
                            <span>Характеристики двери</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="category" class="form-label required">Категория</label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">— выберите —</option>
                                    @foreach(array_keys($price) as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $contract->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="model" class="form-label required">Модель</label>
                                <select class="form-control @error('model') is-invalid @enderror" 
                                        id="model" name="model" required>
                                    <option value="">Сначала выберите категорию</option>
                                </select>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="width" class="form-label required">Ширина (мм)</label>
                                <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                       id="width" name="width" value="{{ old('width', $contract->width) }}" min="850" step="0.01" required>
                                @error('width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="height" class="form-label required">Высота (мм)</label>
                                <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                       id="height" name="height" value="{{ old('height', $contract->height) }}" min="850" step="0.01" required>
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="leaf" class="form-label required">Створка</label>
                                <select class="form-control @error('leaf') is-invalid @enderror" id="leaf" name="leaf" required>
                                    <option value="">— выберите —</option>
                                    <option value="1" {{ old('leaf', $contract->leaf) == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('leaf', $contract->leaf) == '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('leaf', $contract->leaf) == '3' ? 'selected' : '' }}>3</option>
                                </select>
                                @error('leaf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="framugawidth" class="form-label required">Фрамуга боковая</label>
                                <select class="form-control @error('framugawidth') is-invalid @enderror" id="framugawidth" name="framugawidth" required>
                                    <option value="">— выберите —</option>
                                    <option value="1" {{ old('framugawidth', $contract->framugawidth) == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('framugawidth', $contract->framugawidth) == '2' ? 'selected' : '' }}>2</option>
                                    <option value="-" {{ old('framugawidth', $contract->framugawidth) == '-' ? 'selected' : '' }}>–</option>
                                </select>
                                @error('framugawidth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="framugaheight" class="form-label required">Фрамуга верхняя</label>
                                <select class="form-control @error('framugaheight') is-invalid @enderror" id="framugaheight" name="framugaheight" required>
                                    <option value="">— выберите —</option>
                                    <option value="1" {{ old('framugaheight', $contract->framugaheight) == '1' ? 'selected' : '' }}>1</option>
                                    <option value="-" {{ old('framugaheight', $contract->framugaheight) == '-' ? 'selected' : '' }}>–</option>
                                </select>
                                @error('framugaheight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="steel_thickness" class="form-label">Толщина стали (мм)</label>
                                <input type="number" class="form-control @error('steel_thickness') is-invalid @enderror" 
                                       id="steel_thickness" name="steel_thickness" value="{{ old('steel_thickness', $contract->steel_thickness) }}" step="0.01">
                                @error('steel_thickness')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="canvas_thickness" class="form-label">Толщина полотна (мм)</label>
                                <input type="number" class="form-control @error('canvas_thickness') is-invalid @enderror" 
                                       id="canvas_thickness" name="canvas_thickness" value="{{ old('canvas_thickness', $contract->canvas_thickness) }}" step="0.01">
                                @error('canvas_thickness')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="installation" class="form-label">Установка</label>
                                <input type="number" class="form-control @error('installation') is-invalid @enderror" 
                                       id="installation" name="installation" value="{{ old('installation', $contract->installation) }}">
                                @error('installation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="delivery" class="form-label">Доставка</label>
                                <input type="text" class="form-control @error('delivery') is-invalid @enderror" 
                                       id="delivery" name="delivery" value="{{ old('delivery', $contract->delivery) }}">
                                @error('delivery')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Покрытия и материалы -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-palette"></i>
                            <span>Покрытия и материалы</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="metal_cover_hidden" class="form-label">Покрытие металла</label>
                                <input type="text" class="form-control @error('metal_cover_hidden') is-invalid @enderror" 
                                       id="metal_cover_hidden" name="metal_cover_hidden" value="{{ old('metal_cover_hidden', $contract->metal_cover_hidden ?? '') }}">
                                @error('metal_cover_hidden')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="metal_cover_color" class="form-label">Цвет покрытия металла</label>
                                <input type="text" class="form-control @error('metal_cover_color') is-invalid @enderror" 
                                       id="metal_cover_color" name="metal_cover_color" value="{{ old('metal_cover_color', $contract->metal_cover_color ?? '') }}">
                                @error('metal_cover_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="outer_cover" class="form-label required">Покрытие наружной панели</label>
                                <select class="form-control @error('outer_cover') is-invalid @enderror" 
                                        id="outer_cover" name="outer_cover" required>
                                    <option value="">Сначала выберите категорию и модель</option>
                                </select>
                                @error('outer_cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="outer_cover_color" class="form-label">Цвет покрытия наружной панели</label>
                                <input type="text" class="form-control" 
                                       id="outer_cover_color" name="outer_cover_color" 
                                       placeholder="Пример: RAL 9010" value="{{ old('outer_cover_color', $contract->outer_cover_color ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label for="inner_trim" class="form-label required">Внутренняя обшивка</label>
                                <select class="form-control @error('inner_trim') is-invalid @enderror" 
                                        id="inner_trim" name="inner_trim" required>
                                    <option value="">— выберите —</option>
                                </select>
                                @error('inner_trim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="inner_cover" class="form-label required">Покрытие внутренней обшивки</label>
                                <select class="form-control @error('inner_cover') is-invalid @enderror" 
                                        id="inner_cover" name="inner_cover" required>
                                    <option value="">— выберите —</option>
                                </select>
                                @error('inner_cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="glass_unit" class="form-label required">Стеклопакет</label>
                                <select class="form-control @error('glass_unit') is-invalid @enderror" 
                                        id="glass_unit" name="glass_unit" required>
                                    <option value="">— выберите —</option>
                                </select>
                                @error('glass_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="lock" class="form-label required">Замок</label>
                                <select class="form-control @error('lock') is-invalid @enderror" 
                                        id="lock" name="lock" required>
                                    <option value="">— выберите —</option>
                                </select>
                                @error('lock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="handle" class="form-label required">Ручка</label>
                                <input type="text" class="form-control @error('handle') is-invalid @enderror" 
                                       id="handle" name="handle" value="{{ old('handle', $contract->handle) }}" required>
                                @error('handle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительные услуги -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-tools"></i>
                            <span>Дополнительные услуги</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="measurement" class="form-label">Замер</label>
                                <input type="text" class="form-control @error('measurement') is-invalid @enderror" 
                                       id="measurement" name="measurement" value="{{ old('measurement', $contract->measurement) }}">
                                @error('measurement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="extra" class="form-label">Дополнительные услуги</label>
                                <input type="text" class="form-control @error('extra') is-invalid @enderror" 
                                       id="extra" name="extra" value="{{ old('extra', $contract->extra) }}">
                                @error('extra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Финансовая информация -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Финансовая информация</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="order_total" class="form-label required">Общая стоимость</label>
                                <input type="number" class="form-control @error('order_total') is-invalid @enderror" 
                                       id="order_total" name="order_total" value="{{ old('order_total', $contract->order_total) }}" min="0" step="1" required>
                                @error('order_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_deposit" class="form-label required">Предоплата</label>
                                <input type="number" class="form-control @error('order_deposit') is-invalid @enderror" 
                                       id="order_deposit" name="order_deposit" value="{{ old('order_deposit', $contract->order_deposit) }}" min="0" step="1" required>
                                @error('order_deposit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_remainder" class="form-label">Остаток предоплаты</label>
                                <input type="number" class="form-control @error('order_remainder') is-invalid @enderror" 
                                       id="order_remainder" name="order_remainder" value="{{ old('order_remainder', $contract->order_remainder) }}" min="0" step="1">
                                @error('order_remainder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_due" class="form-label required">К оплате после изготовления</label>
                                <input type="number" class="form-control @error('order_due') is-invalid @enderror" 
                                       id="order_due" name="order_due" value="{{ old('order_due', $contract->order_due) }}" min="0" step="1" required>
                                @error('order_due')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Фотографии -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-camera"></i>
                            <span>Фотографии</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="photo" class="form-label">Фото двери</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                @if($contract->photo_path)
                                    <small class="form-text text-muted">Текущее фото: {{ basename($contract->photo_path) }}</small>
                                @endif
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="attachment" class="form-label">Дополнительное фото</label>
                                <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                                       id="attachment" name="attachment" accept="image/*">
                                @if($contract->attachment_path)
                                    <small class="form-text text-muted">Текущий файл: {{ basename($contract->attachment_path) }}</small>
                                @endif
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-save"></i>
                            <span>Действия</span>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save"></i>
                                Сохранить изменения
                            </button>
                            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.show' : (Auth::user()->role === 'manager' ? 'manager.contracts.show' : 'rop.contracts.show'), $contract) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i>
                                Просмотр договора
                            </a>
                            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.index' : (Auth::user()->role === 'manager' ? 'manager.contracts.index' : 'rop.contracts.index')) }}" class="btn btn-cancel">
                                <i class="fas fa-times"></i>
                                Отмена
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.edit-branch-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.page-header {
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
    color: white;
    font-size: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #6b7280;
    margin: 0;
}

.page-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin: 4px 0 0 0;
}

.form-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #f3f4f6;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
    font-weight: 600;
    font-size: 16px;
    color: #374151;
}

.section-header i {
    color: #1ba4e9;
    font-size: 18px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.info-item {
    font-size: 14px;
    color: #6b7280;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    transition: all 0.2s ease;
    background: #fafafa;
}

.form-control:focus {
    outline: none;
    border-color: #1ba4e9;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #ef4444;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-start;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-save {
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.btn-info {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
    color: white;
    box-shadow: 0 2px 4px rgba(6, 182, 212, 0.2);
}

.btn-info:hover {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(6, 182, 212, 0.3);
}

.btn-cancel {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Заполняем модель при выборе категории
document.getElementById('category').addEventListener('change', function() {
    const category = this.value;
    const modelSelect = document.getElementById('model');
    const price = @json($price);
    
    modelSelect.innerHTML = '<option value="">— выберите —</option>';
    
    if (category && price[category]) {
        Object.keys(price[category]).forEach(model => {
            const option = document.createElement('option');
            option.value = model;
            option.textContent = model;
            if (model === '{{ $contract->model }}') {
                option.selected = true;
            }
            modelSelect.appendChild(option);
        });
    }
    
    // Обновляем остальные поля при изменении категории
    updateFields();
});

// Заполняем покрытие наружной панели при выборе модели
document.getElementById('model').addEventListener('change', function() {
    updateFields();
});

// Конфигурация для заполнения полей
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

const fusionModels = @json($fusionModels);

function updateFields() {
    const category = document.getElementById('category').value;
    const model = document.getElementById('model').value;
    const isFusion = fusionModels.includes(model);
    
    // Обновляем покрытие наружной панели
    const outerCoverSelect = document.getElementById('outer_cover');
    outerCoverSelect.innerHTML = '<option value="">— выберите —</option>';
    const covers = isFusion ? config.fusion_outer_cover : config.default_outer_cover[category];
    if (covers) {
        covers.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v;
            opt.textContent = v;
            if (v === '{{ $contract->outer_cover }}') {
                opt.selected = true;
            }
            outerCoverSelect.appendChild(opt);
        });
    }
    
    // Обновляем внутреннюю обшивку
    const innerTrimSelect = document.getElementById('inner_trim');
    innerTrimSelect.innerHTML = '<option value="">— выберите —</option>';
    (config.inner_trim[category] || []).forEach(v => {
        const opt = document.createElement('option');
        opt.value = v;
        opt.textContent = v;
        if (v === '{{ $contract->inner_trim }}') {
            opt.selected = true;
        }
        innerTrimSelect.appendChild(opt);
    });
    
    // Обновляем покрытие внутренней обшивки
    const innerCoverSelect = document.getElementById('inner_cover');
    innerCoverSelect.innerHTML = '<option value="">— выберите —</option>';
    (config.inner_cover[category] || []).forEach(v => {
        const opt = document.createElement('option');
        opt.value = v;
        opt.textContent = v;
        if (v === '{{ $contract->inner_cover }}') {
            opt.selected = true;
        }
        innerCoverSelect.appendChild(opt);
    });
    
    // Обновляем стеклопакет
    const glassUnitSelect = document.getElementById('glass_unit');
    glassUnitSelect.innerHTML = '<option value="">— выберите —</option>';
    (config.glass[category] || []).forEach(v => {
        const opt = document.createElement('option');
        opt.value = v;
        opt.textContent = v;
        if (v === '{{ $contract->glass_unit }}') {
            opt.selected = true;
        }
        glassUnitSelect.appendChild(opt);
    });
    
    // Обновляем замок
    const lockSelect = document.getElementById('lock');
    lockSelect.innerHTML = '<option value="">— выберите —</option>';
    (config.lock[category] || []).forEach(v => {
        const opt = document.createElement('option');
        opt.value = v;
        opt.textContent = v;
        if (v === '{{ $contract->lock }}') {
            opt.selected = true;
        }
        lockSelect.appendChild(opt);
    });
}

// Заполняем внутреннюю обшивку
document.addEventListener('DOMContentLoaded', function() {
    // Заполняем модель и покрытие при загрузке страницы
    if (document.getElementById('category').value) {
        document.getElementById('category').dispatchEvent(new Event('change'));
    }
    
    // Если категория не выбрана, все равно заполняем поля существующими значениями
    updateFields();
});
</script>
@endsection
