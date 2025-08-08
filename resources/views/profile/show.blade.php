@extends('layouts.admin')
@section('title', 'Мой профиль')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Мой профиль</h1>
                            <p class="page-subtitle">Управление личной информацией и настройками</p>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Основная информация -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-user"></i>
                        <span>Основная информация</span>
                    </div>
                    
                    <div class="personnel-section">
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">{{ $user->name }}</div>
                                <div class="personnel-list">
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="personnel-tag branch-tag">Администратор</span>
                                            @break
                                        @case('manager')
                                            <span class="personnel-tag manager-tag">Менеджер</span>
                                            @break
                                        @case('rop')
                                            <span class="personnel-tag contract-tag">РОП</span>
                                            @break
                                        @default
                                            <span class="personnel-tag client-tag">{{ ucfirst($user->role) }}</span>
                                    @endswitch
                                    <span class="personnel-tag date-tag">{{ $user->email }}</span>
                                    @if($user->phone)
                                        <span class="personnel-tag amount-tag">{{ $user->phone }}</span>
                                    @endif
                                    @if($user->role !== 'admin' && $user->branch)
                                        <span class="personnel-tag code-tag">{{ $user->branch->name }}</span>
                                    @endif
                                    <span class="personnel-tag date-tag">Регистрация: {{ $user->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(in_array($user->role, ['admin', 'manager']))
                <!-- Встроенное редактирование профиля для админа -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-user-edit"></i>
                        <span>Редактирование профиля</span>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" class="edit-form">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label required">
                                    <i class="fas fa-user"></i>
                                    Имя пользователя
                                </label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label required">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Телефон
                                </label>
                                <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="section-header" style="margin-top: 8px;">
                            <i class="fas fa-lock"></i>
                            <span>Изменение пароля</span>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key"></i>
                                    Текущий пароль
                                </label>
                                <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="new_password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Новый пароль
                                </label>
                                <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                                @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Подтвердите новый пароль
                                </label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <!-- Статистика (для не-админов) -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Статистика</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $user->contracts->count() }}</div>
                                <div class="stat-label">Всего договоров</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $user->contracts->where('date', '>=', now()->startOfMonth())->count() }}</div>
                                <div class="stat-label">За этот месяц</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($user->contracts->sum('order_total'), 0, ',', ' ') }} ₸</div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $user->created_at->diffForHumans() }}</div>
                                <div class="stat-label">В системе</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Последние договоры (для не-админов) -->
                @if($user->contracts->count() > 0)
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-history"></i>
                        <span>Последние договоры</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($user->contracts->take(5) as $contract)
                        <div class="personnel-item contract-item">
                            <div class="personnel-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Договор №{{ $contract->contract_number }}</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag date-tag">{{ $contract->date->format('d.m.Y') }}</span>
                                    <span class="personnel-tag amount-tag">{{ number_format($contract->order_total, 0, ',', ' ') }} ₸</span>
                                    <span class="personnel-tag client-tag">{{ $contract->client }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

<style>
/* Стили для профиля в админской панели */
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

/* Формы */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.form-group { display: flex; flex-direction: column; }

.form-label { display: flex; align-items: center; gap: 8px; font-weight: 600; color: #111827; margin-bottom: 8px; font-size: 14px; }
.form-label.required::after { content: " *"; color: #ef4444; }

.form-control { padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: all 0.2s ease; background: #f9fafb; }
.form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); background: #fff; }
.form-control.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
.invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 4px; }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; }
.btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; }
.btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }

/* Статистика и списки */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; transition: all 0.2s ease; }
.stat-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; }
.stat-content { flex: 1; }
.stat-number { font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

.personnel-section { display: flex; flex-direction: column; gap: 16px; }
.personnel-item { display: flex; align-items: flex-start; padding: 16px; background: #fafafa; border-radius: 8px; border: 1px solid #f0f0f0; transition: all 0.2s ease; }
.personnel-item:hover { background: #f8f9fa; border-color: #e9ecef; transform: translateY(-1px); }
.personnel-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0; }
.personnel-item .personnel-icon { background: #eff6ff; color: #2563eb; }
.contract-item .personnel-icon { background: #f0fdf4; color: #166534; }
.personnel-content { flex: 1; min-width: 0; }
.personnel-title { font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
.personnel-list { display: flex; flex-wrap: wrap; gap: 6px; }
.personnel-tag { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 500; display: inline-block; transition: all 0.2s ease; }
.branch-tag { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.manager-tag { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
.contract-tag { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.code-tag { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.client-tag { background: #f3e8ff; color: #7c3aed; border: 1px solid #c7d2fe; }
.amount-tag { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.date-tag { background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; }

/* Анимации */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.form-section { animation: fadeIn 0.3s ease-out; }
.form-section:nth-child(1) { animation-delay: 0.1s; }
.form-section:nth-child(2) { animation-delay: 0.2s; }
.form-section:nth-child(3) { animation-delay: 0.3s; }

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container { padding: 16px; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style> 