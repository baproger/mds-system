@extends('layouts.admin')

@section('title', 'Добавить пользователя')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Создать пользователя</h1>
                            <p class="page-subtitle">Добавление нового пользователя в систему</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="edit-form">
                    @csrf
                    
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
                                       id="name" name="name" value="{{ old('name') }}" required>
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
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password" class="form-label required">
                                    <i class="fas fa-lock"></i>
                                    Пароль
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <small class="form-text">Минимум 8 символов</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label required">
                                    <i class="fas fa-lock"></i>
                                    Подтверждение пароля
                                </label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                                <small class="form-text">Повторите пароль для подтверждения</small>
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
                                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                                            Менеджер
                                        </option>
                                        <option value="rop" {{ old('role') == 'rop' ? 'selected' : '' }}>
                                            РОП
                                        </option>
                                    </optgroup>
                                    <optgroup label="Администрация">
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Администратор системы
                                        </option>
                                        <option value="director" {{ old('role') == 'director' ? 'selected' : '' }}>
                                            Директор
                                        </option>
                                        <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>
                                            Бухгалтер
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
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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

                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-info-circle"></i>
                            <span>Информация о ролях</span>
                        </div>
                        
                        <div class="personnel-section">
                            <div class="personnel-item role-info-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Роли продаж</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag manager-tag">Менеджер - работа с клиентами</span>
                                        <span class="personnel-tag rop-tag">РОП - руководитель отдела продаж</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="personnel-item admin-info-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Административные роли</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag admin-tag">Администратор - полный доступ</span>
                                        <span class="personnel-tag director-tag">Директор - управление системой</span>
                                        <span class="personnel-tag accountant-tag">Бухгалтер - финансовая отчетность</span>
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
                            Создать пользователя
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

.form-label.required::after {
    content: " *";
    color: #ef4444;
}

.form-label i {
    color: #6b7280;
    font-size: 14px;
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
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}

.form-text {
    color: #6b7280;
    font-size: 12px;
    margin-top: 4px;
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

.role-info-item .personnel-icon {
    background: #f3e8ff;
    color: #7c3aed;
}

.admin-info-item .personnel-icon {
    background: #fef3c7;
    color: #d97706;
}

.personnel-content {
    flex: 1;
    min-width: 0;
}

.personnel-title {
    font-weight: 600;
    font-size: 13px;
    color: #374151;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.personnel-list {
    display: flex;
    flex-direction: column;
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
    color: #7c3aed;
    border: 1px solid #c7d2fe;
}

.rop-tag:hover {
    background: #e0e7ff;
    transform: scale(1.02);
}

.manager-tag {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
}

.manager-tag:hover {
    background: #e2e8f0;
    transform: scale(1.02);
}

.admin-tag {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fcd34d;
}

.admin-tag:hover {
    background: #fde68a;
    transform: scale(1.02);
}

.director-tag {
    background: #fce7f3;
    color: #be185d;
    border: 1px solid #f9a8d4;
}

.director-tag:hover {
    background: #fbcfe8;
    transform: scale(1.02);
}

.accountant-tag {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.accountant-tag:hover {
    background: #d1fae5;
    transform: scale(1.02);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
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
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
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

.personnel-item {
    animation: fadeIn 0.3s ease-out;
}

.personnel-item:nth-child(1) { animation-delay: 0.1s; }
.personnel-item:nth-child(2) { animation-delay: 0.2s; }

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
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection 