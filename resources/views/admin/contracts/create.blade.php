@extends('layouts.admin')

@section('title', 'Новый договор')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
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

                <div class="form-section">
                    <form method="POST" action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.store' : (Auth::user()->role === 'manager' ? 'manager.contracts.store' : 'rop.contracts.store')) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Основная информация -->
                        <div class="section-header">
                            <i class="fas fa-info-circle"></i>
                            <span>Основная информация</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="contract_number" class="form-label required">Номер договора</label>
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
                                    <small class="form-text text-muted">Диапазон для филиала "{{ $userBranch->name }}": {{ $range }}</small>
                                @endif
                                @error('contract_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="date" class="form-label required">Дата договора</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
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
                                <label for="payment" class="form-label">Способ оплаты</label>
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
                                       id="client" name="client" value="{{ old('client') }}" required>
                                @error('client')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="instagram" class="form-label required">Instagram</label>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                       id="instagram" name="instagram" value="{{ old('instagram') }}" required>
                                @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="iin" class="form-label required">ИИН</label>
                                <input type="text" class="form-control @error('iin') is-invalid @enderror" 
                                       id="iin" name="iin" value="{{ old('iin') }}" maxlength="12" required>
                                @error('iin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label required">Телефон</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone2" class="form-label required">Дополнительный телефон</label>
                                <input type="tel" class="form-control @error('phone2') is-invalid @enderror" 
                                       id="phone2" name="phone2" value="{{ old('phone2') }}" required>
                                @error('phone2')
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
                                    <option value="Lux" {{ old('category') == 'Lux' ? 'selected' : '' }}>Lux</option>
                                    <option value="Premium" {{ old('category') == 'Premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="Comfort" {{ old('category') == 'Comfort' ? 'selected' : '' }}>Comfort</option>
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
                                </select>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="width" class="form-label required">Ширина (мм)</label>
                                <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                       id="width" name="width" value="{{ old('width') }}" min="850" required>
                                @error('width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="height" class="form-label required">Высота (мм)</label>
                                <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                       id="height" name="height" value="{{ old('height') }}" min="850" required>
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительные характеристики -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-cogs"></i>
                            <span>Дополнительные характеристики</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="outer_cover" class="form-label required">Внешняя отделка</label>
                                <select class="form-control @error('outer_cover') is-invalid @enderror" 
                                        id="outer_cover" name="outer_cover" required>
                                    <option value="">— выберите —</option>
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
                                </select>
                                @error('inner_trim')
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
                        </div>
                    </div>

                    <!-- Стоимость -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Стоимость</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="order_total" class="form-label required">Общая сумма</label>
                                <input type="number" class="form-control @error('order_total') is-invalid @enderror" 
                                       id="order_total" name="order_total" value="{{ old('order_total') }}" min="0" required>
                                @error('order_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_deposit" class="form-label required">Предоплата</label>
                                <input type="number" class="form-control @error('order_deposit') is-invalid @enderror" 
                                       id="order_deposit" name="order_deposit" value="{{ old('order_deposit') }}" min="0" required>
                                @error('order_deposit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_remainder" class="form-label required">Остаток</label>
                                <input type="number" class="form-control @error('order_remainder') is-invalid @enderror" 
                                       id="order_remainder" name="order_remainder" value="{{ old('order_remainder') }}" min="0" required>
                                @error('order_remainder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="order_due" class="form-label required">К оплате</label>
                                <input type="number" class="form-control @error('order_due') is-invalid @enderror" 
                                       id="order_due" name="order_due" value="{{ old('order_due') }}" min="0" required>
                                @error('order_due')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="form-actions">
                        <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Создать договор
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
/* Стили для создания договора в админской панели */
.edit-branch-container {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
}

.page-header {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #f3f4f6;
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
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-label.required::after {
    content: " *";
    color: #ef4444;
}

.form-control {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #f9fafb;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
}

.form-control.is-invalid {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.invalid-feedback {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}

.form-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 24px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

/* Buttons */
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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
    color: #111827;
    transform: translateY(-1px);
}

/* Анимации */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.form-section {
    animation: fadeIn 0.3s ease-out;
}

.form-section:nth-child(1) { animation-delay: 0.1s; }
.form-section:nth-child(2) { animation-delay: 0.2s; }
.form-section:nth-child(3) { animation-delay: 0.3s; }
.form-section:nth-child(4) { animation-delay: 0.4s; }
.form-section:nth-child(5) { animation-delay: 0.5s; }

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
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
