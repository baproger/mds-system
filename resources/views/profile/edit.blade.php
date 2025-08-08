@extends('layouts.admin')
@section('title', 'Редактировать профиль')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Редактировать профиль</h1>
                            <p class="page-subtitle">Изменение личной информации и настроек</p>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="edit-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Основная информация -->
                        <div class="section-header">
                            <i class="fas fa-info-circle"></i>
                            <span>Основная информация</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label required">
                                    <i class="fas fa-user"></i>
                                    Имя пользователя
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label required">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Телефон
                                </label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Изменение пароля -->
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
                                        Оставьте поля пароля пустыми, если не хотите менять пароль. 
                                        Новый пароль должен содержать минимум 8 символов.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key"></i>
                                    Текущий пароль
                                </label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="new_password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Новый пароль
                                </label>
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password">
                                @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Подтвердите новый пароль
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation">
                            </div>
                        </div>
                    </div>

                    <!-- Информация о роли -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-user-tag"></i>
                            <span>Информация о роли</span>
                        </div>
                        
                        <div class="role-info">
                            <div class="role-item">
                                <span class="role-label">Роль:</span>
                                <span class="role-value">{{ ucfirst($user->role) }}</span>
                            </div>
                            @if($user->role !== 'admin' && $user->branch)
                            <div class="role-item">
                                <span class="role-label">Филиал:</span>
                                <span class="role-value">{{ $user->branch->name }}</span>
                            </div>
                            @endif
                            <div class="role-item">
                                <span class="role-label">Дата регистрации:</span>
                                <span class="role-value">{{ $user->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="form-actions">
                        <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
/* Стили для редактирования профиля в админской панели */
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

/* Password Info */
.password-info {
    margin-bottom: 20px;
}

.info-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: #f0f9ff;
    border-radius: 8px;
    border: 1px solid #bae6fd;
}

.info-icon {
    color: #0369a1;
    font-size: 16px;
    margin-top: 2px;
}

.info-content {
    flex: 1;
}

.info-title {
    font-weight: 600;
    color: #0369a1;
    margin-bottom: 4px;
    font-size: 14px;
}

.info-text {
    color: #0c4a6e;
    font-size: 13px;
    line-height: 1.5;
}

/* Role Info */
.role-info {
    background: #f9fafb;
    border-radius: 8px;
    padding: 16px;
    border-left: 3px solid #667eea;
}

.role-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
}

.role-item:last-child {
    border-bottom: none;
}

.role-label {
    font-weight: 600;
    color: #111827;
    font-size: 14px;
}

.role-value {
    color: #6b7280;
    font-weight: 500;
    font-size: 14px;
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