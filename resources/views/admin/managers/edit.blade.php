@extends('layouts.admin')

@section('title', 'Редактировать менеджера')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Информация о менеджере</h1>
                            <p class="page-subtitle">Управление данными менеджера</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.managers.update', $manager) }}" class="edit-form">
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
                                    Имя менеджера
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $manager->name) }}" required>
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
                                       id="email" name="email" value="{{ old('email', $manager->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Телефон
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $manager->phone) }}">
                                <small class="form-text">Номер телефона для связи</small>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="branch_id" class="form-label required">
                                    <i class="fas fa-building"></i>
                                    Филиал
                                </label>
                                <select class="form-control @error('branch_id') is-invalid @enderror" 
                                        id="branch_id" name="branch_id" required>
                                    <option value="">Выберите филиал</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $manager->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
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
                                    <option value="manager" {{ old('role', $manager->role) == 'manager' ? 'selected' : '' }}>Менеджер</option>
                                    <option value="rop" {{ old('role', $manager->role) == 'rop' ? 'selected' : '' }}>РОП</option>
                                </select>
                                <small class="form-text">РОП - руководитель отдела продаж</small>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-chart-bar"></i>
                            <span>Статистика менеджера</span>
                        </div>
                        
                        <div class="stats-grid">
                            <!--<div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $manager->contracts_count ?? 0 }}</div>
                                    <div class="stat-label">Договоров</div>
                                </div>
                            </div>-->
                            
                            <!--<div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ number_format($manager->total_revenue ?? 0) }} ₸</div>
                                    <div class="stat-label">Доход</div>
                                </div>
                            </div>-->
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $manager->created_at ? $manager->created_at->format('d.m.Y') : 'N/A' }}</div>
                                    <div class="stat-label">Дата регистрации</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $manager->branch ? $manager->branch->name : 'Не назначен' }}</div>
                                    <div class="stat-label">Филиал</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Информация о роли -->
                        <div class="personnel-section">
                            <div class="personnel-item role-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Роль в системе</div>
                                    <div class="personnel-list">
                                        @if($manager->role == 'rop')
                                            <span class="personnel-tag rop-tag">РОП - Руководитель отдела продаж</span>
                                        @else
                                            <span class="personnel-tag manager-tag">Менеджер</span>
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
                                        @if($manager->branch)
                                            <span class="personnel-tag branch-tag">{{ $manager->branch->name }}</span>
                                            <span class="personnel-tag code-tag">{{ $manager->branch->code }}</span>
                                        @else
                                            <span class="empty-state">Не назначен</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.managers.index') }}" class="btn btn-cancel">
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
    border-color: #1ba4e9;
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
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
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 16px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
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
    color: #7c3aed;
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
    color: #374151;
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
    color: #9ca3af;
    font-size: 12px;
    font-style: italic;
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
    .btn i { color: #fff !important; }    display: inline-flex;
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
    .btn i { color: #fff !important; }    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    .btn i { color: #fff !important; }    background: #e5e7eb;
    transform: translateY(-1px);
}

.btn-save {
    .btn i { color: #fff !important; }    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-save:hover {
    .btn i { color: #fff !important; }    transform: translateY(-1px);
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
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
    .btn i { color: #fff !important; }        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection 