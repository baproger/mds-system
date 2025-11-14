@extends('layouts.admin')

@section('title', 'Редактировать пользователя')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Информация о пользователе</h1>
                            <p class="page-subtitle">Управление данными пользователя</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="edit-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-edit"></i>
                            <span>Основная информация</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label required">
                                    <i class="fas fa-user"></i>
                                    Имя пользователя
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label required">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="role" class="form-label required">
                                    <i class="fas fa-user-tag"></i>
                                    Роль
                                </label>
                                <select class="form-control @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Выберите роль</option>
                                    <optgroup label="Продажи">
                                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                                            Менеджер
                                        </option>
                                        <option value="rop" {{ old('role', $user->role) == 'rop' ? 'selected' : '' }}>
                                            РОП
                                        </option>
                                    </optgroup>
                                    <optgroup label="Администрация">
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Администратор системы
                                        </option>
                                    </optgroup>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="branch_id" class="form-label">
                                    <i class="fas fa-building"></i>
                                    Филиал
                                </label>
                                <select class="form-control @error('branch_id') is-invalid @enderror" 
                                        id="branch_id" name="branch_id">
                                    <option value="">Выберите филиал</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text">Необязательно для административных ролей</small>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Секция изменения пароля -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-lock"></i>
                            <span>Изменение пароля</span>
                        </div>
                        
                        <div class="password-info">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-title">Важная информация</div>
                                    <div class="info-text">
                                        Оставьте поля пароля пустыми, если не хотите изменять пароль пользователя. 
                                        Новый пароль должен содержать минимум 8 символов.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Новый пароль
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                <small class="form-text">Минимум 8 символов. Оставьте пустым, чтобы не изменять</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Подтверждение пароля
                                </label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation">
                                <small class="form-text">Повторите новый пароль для подтверждения</small>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-chart-bar"></i>
                            <span>Статистика пользователя</span>
                        </div>
                        
                        <!--<div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $user->created_at ? $user->created_at->format('d.m.Y') : 'N/A' }}</div>
                                    <div class="stat-label">Дата регистрации</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $user->updated_at ? $user->updated_at->format('d.m.Y') : 'N/A' }}</div>
                                    <div class="stat-label">Последнее обновление</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $user->branch ? $user->branch->name : 'Не назначен' }}</div>
                                    <div class="stat-label">Филиал</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ ucfirst($user->role) }}</div>
                                    <div class="stat-label">Роль</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Информация о роли и филиале -->
                        <div class="personnel-section">
                            <div class="personnel-item role-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Роль в системе</div>
                                    <div class="personnel-list">
                                        @if($user->role == 'rop')
                                            <span class="personnel-tag rop-tag">РОП - Руководитель отдела продаж</span>
                                        @elseif($user->role == 'manager')
                                            <span class="personnel-tag manager-tag">Менеджер</span>
                                        @elseif($user->role == 'admin')
                                            <span class="personnel-tag admin-tag">Администратор системы</span>
                                        @else
                                            <span class="empty-state">Не назначена</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="personnel-item branch-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Прикрепленный филиал</div>
                                    <div class="personnel-list">
                                        @if($user->branch)
                                            <span class="personnel-tag branch-tag">{{ $user->branch->name }}</span>
                                            <span class="personnel-tag code-tag">{{ $user->branch->code }}</span>
                                        @else
                                            <span class="empty-state">Не назначен</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-cancel">
                            <i class="fas fa-times"></i>
                            Отмена
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save"></i>
                            Сохранить изменения
                        </button>
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
    border-bottom: 1px solid var(--border-color);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: #e0f2fe;
    color: #1ba4e9;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1ba4e9;
    font-size: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-secondary);
    margin: 0;
}

.page-subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 4px 0 0 0;
}

.form-section {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--bg-tertiary);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--bg-tertiary);
    font-weight: 600;
    font-size: 16px;
    color: var(--text-primary);
}

.section-header i {
    color: #fff;
    font-size: 18px;
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
    color: var(--text-primary);
    margin-bottom: 8px;
}

.form-label.required::after {
    content: " *";
    color: #ef4444;
}

.form-label i {
    color: var(--text-secondary);
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #fafafa;
}

.form-control:focus {
    outline: none;
    border-color: #1ba4e9;
    background: var(--bg-card);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.invalid-feedback {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}

.form-text {
    color: var(--text-secondary);
    font-size: 12px;
    margin-top: 4px;
}

/* Стили для информационной карточки пароля */
.password-info {
    margin-bottom: 24px;
}

.info-card {
    display: flex;
    align-items: flex-start;
    padding: 16px;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #bae6fd;
    border-radius: 12px;
    gap: 16px;
}

.info-icon {
    width: 32px;
    height: 32px;
    background: #0369a1;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1ba4e9;
    font-size: 16px;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-title {
    font-weight: 600;
    font-size: 14px;
    color: #0369a1;
    margin-bottom: 4px;
}

.info-text {
    font-size: 13px;
    color: #0c4a6e;
    line-height: 1.4;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-secondary) 100%);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: #e0f2fe;
    color: #1ba4e9;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1ba4e9;
    font-size: 20px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.personnel-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.personnel-item {
    display: flex;
    align-items: flex-start;
    padding: 16px;
    background: #fafafa;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.personnel-item:hover {
    background: #f8f9fa;
    border-color: #e9ecef;
    transform: translateY(-1px);
}

.personnel-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.role-item .personnel-icon {
    background: #f3e8ff;
    color: #0284c7;
}

.branch-item .personnel-icon {
    background: #eff6ff;
    color: #2563eb;
}

.personnel-content {
    flex: 1;
    min-width: 0;
}

.personnel-title {
    font-weight: 600;
    font-size: 13px;
    color: var(--text-primary);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.personnel-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.personnel-tag {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    transition: all 0.2s ease;
}

.rop-tag {
    background: #eef2ff;
    color: #0284c7;
    border: 1px solid #c7d2fe;
}

.rop-tag:hover {
    background: #e0e7ff;
    transform: scale(1.02);
}

.manager-tag {
    background: var(--bg-secondary);
    color: #475569;
    border: 1px solid #cbd5e1;
}

.manager-tag:hover {
    background: var(--border-color);
    transform: scale(1.02);
}

.admin-tag {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #1ba4e9;
}

.default-tag {
    background: linear-gradient(135deg, var(--text-secondary), #4b5563);
    color: #1ba4e9;
}

.branch-tag {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.branch-tag:hover {
    background: #dbeafe;
    transform: scale(1.02);
}

.code-tag {
    background: #f0f9ff;
    color: #0369a1;
    border: 1px solid #bae6fd;
}

.code-tag:hover {
    background: #e0f2fe;
    transform: scale(1.02);
}

.empty-state {
    color: var(--text-muted);
    font-size: 12px;
    font-style: italic;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
    margin-top: 32px;
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

.btn-cancel {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    background: var(--border-color);
    transform: translateY(-1px);
}

.btn-save {
    background: #e0f2fe;
    color: #1ba4e9;
    color: #1ba4e9;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
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

.personnel-item {
    animation: fadeIn 0.3s ease-out;
}

.personnel-item:nth-child(1) { animation-delay: 0.1s; }
.personnel-item:nth-child(2) { animation-delay: 0.2s; }

.info-card {
    animation: fadeIn 0.3s ease-out;
    animation-delay: 0.1s;
}

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endsection 