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
                            <h1 class="page-title">Редактировать договор</h1>
                            <p class="page-subtitle">Редактирование договора №{{ $contract->contract_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <form method="POST" action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.update' : (Auth::user()->role === 'manager' ? 'manager.contracts.update' : 'rop.contracts.update'), $contract) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Основная информация -->
                        <div class="section-header">
                            <i class="fas fa-info-circle"></i>
                            <span>Основная информация</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="contract_number" class="form-label required">Номер договора</label>
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
                                <label for="date" class="form-label required">Дата договора</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', $contract->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="manager" class="form-label required">Менеджер</label>
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
                                <label for="payment" class="form-label">Способ оплаты</label>
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
                                <label for="instagram" class="form-label required">Instagram</label>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                       id="instagram" name="instagram" value="{{ old('instagram', $contract->instagram) }}" required>
                                @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="iin" class="form-label required">ИИН</label>
                                <input type="text" class="form-control @error('iin') is-invalid @enderror" 
                                       id="iin" name="iin" value="{{ old('iin', $contract->iin) }}" maxlength="12" required>
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
                                <label for="phone2" class="form-label required">Доп. телефон</label>
                                <input type="text" class="form-control @error('phone2') is-invalid @enderror" 
                                       id="phone2" name="phone2" value="{{ old('phone2', $contract->phone2) }}" required>
                                @error('phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Технические характеристики -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-cogs"></i>
                            <span>Технические характеристики</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="category" class="form-label required">Категория</label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">— выберите —</option>
                                    <option value="Lux" {{ old('category', $contract->category) == 'Lux' ? 'selected' : '' }}>Lux</option>
                                    <option value="Premium" {{ old('category', $contract->category) == 'Premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="Comfort" {{ old('category', $contract->category) == 'Comfort' ? 'selected' : '' }}>Comfort</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="model" class="form-label required">Модель</label>
                                <select class="form-control @error('model') is-invalid @enderror" 
                                        id="model" name="model" required>
                                    <option value="">— выберите —</option>
                                    @foreach($fusionModels as $model)
                                        <option value="{{ $model }}" {{ old('model', $contract->model) == $model ? 'selected' : '' }}>
                                            {{ $model }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="width" class="form-label required">Ширина (мм)</label>
                                <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                       id="width" name="width" value="{{ old('width', $contract->width) }}" min="850" required>
                                @error('width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="height" class="form-label required">Высота (мм)</label>
                                <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                       id="height" name="height" value="{{ old('height', $contract->height) }}" min="850" required>
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="framugawidth" class="form-label required">Ширина фрамуги (мм)</label>
                                <input type="number" class="form-control @error('framugawidth') is-invalid @enderror" 
                                       id="framugawidth" name="framugawidth" value="{{ old('framugawidth', $contract->framugawidth) }}" required>
                                @error('framugawidth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="framugaheight" class="form-label required">Высота фрамуги (мм)</label>
                                <input type="number" class="form-control @error('framugaheight') is-invalid @enderror" 
                                       id="framugaheight" name="framugaheight" value="{{ old('framugaheight', $contract->framugaheight) }}" required>
                                @error('framugaheight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="outer_cover" class="form-label required">Внешняя обшивка</label>
                                <select class="form-control @error('outer_cover') is-invalid @enderror" 
                                        id="outer_cover" name="outer_cover" required>
                                    <option value="">— выберите —</option>
                                    <option value="Металл" {{ old('outer_cover', $contract->outer_cover) == 'Металл' ? 'selected' : '' }}>Металл</option>
                                    <option value="Пластик" {{ old('outer_cover', $contract->outer_cover) == 'Пластик' ? 'selected' : '' }}>Пластик</option>
                                    <option value="Дерево" {{ old('outer_cover', $contract->outer_cover) == 'Дерево' ? 'selected' : '' }}>Дерево</option>
                                </select>
                                @error('outer_cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="inner_trim" class="form-label required">Внутренняя отделка</label>
                                <select class="form-control @error('inner_trim') is-invalid @enderror" 
                                        id="inner_trim" name="inner_trim" required>
                                    <option value="">— выберите —</option>
                                    <option value="МДФ" {{ old('inner_trim', $contract->inner_trim) == 'МДФ' ? 'selected' : '' }}>МДФ</option>
                                    <option value="Пластик" {{ old('inner_trim', $contract->inner_trim) == 'Пластик' ? 'selected' : '' }}>Пластик</option>
                                    <option value="Дерево" {{ old('inner_trim', $contract->inner_trim) == 'Дерево' ? 'selected' : '' }}>Дерево</option>
                                </select>
                                @error('inner_trim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="inner_cover" class="form-label required">Внутренняя обшивка</label>
                                <select class="form-control @error('inner_cover') is-invalid @enderror" 
                                        id="inner_cover" name="inner_cover" required>
                                    <option value="">— выберите —</option>
                                    <option value="МДФ" {{ old('inner_cover', $contract->inner_cover) == 'МДФ' ? 'selected' : '' }}>МДФ</option>
                                    <option value="Пластик" {{ old('inner_cover', $contract->inner_cover) == 'Пластик' ? 'selected' : '' }}>Пластик</option>
                                    <option value="Дерево" {{ old('inner_cover', $contract->inner_cover) == 'Дерево' ? 'selected' : '' }}>Дерево</option>
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
                                    <option value="Однокамерный" {{ old('glass_unit', $contract->glass_unit) == 'Однокамерный' ? 'selected' : '' }}>Однокамерный</option>
                                    <option value="Двухкамерный" {{ old('glass_unit', $contract->glass_unit) == 'Двухкамерный' ? 'selected' : '' }}>Двухкамерный</option>
                                    <option value="Трехкамерный" {{ old('glass_unit', $contract->glass_unit) == 'Трехкамерный' ? 'selected' : '' }}>Трехкамерный</option>
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
                                    <option value="Классический" {{ old('lock', $contract->lock) == 'Классический' ? 'selected' : '' }}>Классический</option>
                                    <option value="Электронный" {{ old('lock', $contract->lock) == 'Электронный' ? 'selected' : '' }}>Электронный</option>
                                    <option value="Комбинированный" {{ old('lock', $contract->lock) == 'Комбинированный' ? 'selected' : '' }}>Комбинированный</option>
                                </select>
                                @error('lock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="handle" class="form-label required">Ручка</label>
                                <select class="form-control @error('handle') is-invalid @enderror" 
                                        id="handle" name="handle" required>
                                    <option value="">— выберите —</option>
                                    <option value="Классическая" {{ old('handle', $contract->handle) == 'Классическая' ? 'selected' : '' }}>Классическая</option>
                                    <option value="Современная" {{ old('handle', $contract->handle) == 'Современная' ? 'selected' : '' }}>Современная</option>
                                    <option value="Роскошная" {{ old('handle', $contract->handle) == 'Роскошная' ? 'selected' : '' }}>Роскошная</option>
                                </select>
                                @error('handle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Финансовая информация -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-money-bill"></i>
                            <span>Финансовая информация</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="order_total" class="form-label required">Общая сумма (₸)</label>
                                <input type="number" class="form-control @error('order_total') is-invalid @enderror" 
                                       id="order_total" name="order_total" value="{{ old('order_total', $contract->order_total) }}" min="0" required>
                                @error('order_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_deposit" class="form-label required">Предоплата (₸)</label>
                                <input type="number" class="form-control @error('order_deposit') is-invalid @enderror" 
                                       id="order_deposit" name="order_deposit" value="{{ old('order_deposit', $contract->order_deposit) }}" min="0" required>
                                @error('order_deposit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_remainder" class="form-label required">Остаток (₸)</label>
                                <input type="number" class="form-control @error('order_remainder') is-invalid @enderror" 
                                       id="order_remainder" name="order_remainder" value="{{ old('order_remainder', $contract->order_remainder) }}" min="0" required>
                                @error('order_remainder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_due" class="form-label required">К оплате (₸)</label>
                                <input type="number" class="form-control @error('order_due') is-invalid @enderror" 
                                       id="order_due" name="order_due" value="{{ old('order_due', $contract->order_due) }}" min="0" required>
                                @error('order_due')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Файлы -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-paperclip"></i>
                            <span>Файлы</span>
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    color: #111827;
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
    color: #667eea;
    font-size: 18px;
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
    border-color: #667eea;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
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
@endsection
