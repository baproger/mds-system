@extends('layouts.admin')

@section('title', 'Редактировать филиал')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Информация о филиале</h1>
                            <p class="page-subtitle">Управление данными филиала</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="edit-form">
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
                                    <i class="fas fa-tag"></i>
                                    Название филиала
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $branch->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="code" class="form-label required">
                                    <i class="fas fa-code"></i>
                                    Код филиала
                                </label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $branch->code) }}" required>
                                <small class="form-text">Уникальный код филиала (например: SHY-PP, ALA-TST)</small>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="contract_counter" class="form-label required">
                                    <i class="fas fa-hashtag"></i>
                                    Начальный номер договоров
                                </label>
                                <input type="number" class="form-control @error('contract_counter') is-invalid @enderror" 
                                       id="contract_counter" name="contract_counter" 
                                       value="{{ old('contract_counter', $branch->contract_counter) }}" 
                                       min="1" required>
                                <small class="form-text">Номер, с которого начинается нумерация договоров в этом филиале</small>
                                @error('contract_counter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-chart-bar"></i>
                            <span>Статистика филиала</span>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $branch->users_count ?? 0 }}</div>
                                    <div class="stat-label">Пользователей</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $branch->contracts_count ?? 0 }}</div>
                                    <div class="stat-label">Договоров</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ $branch->sales_staff_count ?? 0 }}</div>
                                    <div class="stat-label">Менеджеров</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">{{ number_format($branch->total_revenue ?? 0) }} ₸</div>
                                    <div class="stat-label">Доход</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Компактная секция персонала -->
                        <div class="personnel-section">
                            <!-- РОП -->
                            <div class="personnel-item rop-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">РОП</div>
                                    <div class="personnel-list">
                                        @if($branch->rop->count() > 0)
                                            @foreach($branch->rop as $rop)
                                                <span class="personnel-tag rop-tag"><i class="fas fa-crown tag-icon"></i>{{ $rop->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="empty-state">Не назначен</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Менеджеры -->
                            <div class="personnel-item manager-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Менеджеры</div>
                                    <div class="personnel-list">
                                        @if($branch->managers->count() > 0)
                                            @foreach($branch->managers as $manager)
                                                <span class="personnel-tag manager-tag"><i class="fas fa-user-tie tag-icon"></i>{{ $manager->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="empty-state">Не назначены</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Статистика -->
                            <div class="personnel-item stats-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Всего менеджеров</div>
                                    <div class="personnel-value">{{ $branch->sales_staff_count ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.branches.index') }}" class="btn btn-cancel">
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
    color: #1ba4e9;
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

.rop-item .personnel-icon {
    background: #f3e8ff;
    color: #7c3aed;
}

.manager-item .personnel-icon {
    background: #eff6ff;
    color: #2563eb;
}

.stats-item .personnel-icon {
    background: #ecfdf5;
    color: #059669;
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
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    transition: all 0.2s ease;
    border: 1px solid;
}

.rop-tag { background: #eef2ff; color: #7c3aed; border-color: #c7d2fe; }

.rop-tag:hover {
    background: #e0e7ff;
    transform: scale(1.02);
}

.manager-tag { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }

.manager-tag:hover {
    background: #e2e8f0;
    transform: scale(1.02);
}

.personnel-value {
    font-size: 18px;
    font-weight: 700;
    color: #059669;
}

.tag-icon { margin-right: 6px; opacity: 0.85; }

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

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.btn-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(27, 164, 233, 0.3);
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
.personnel-item:nth-child(3) { animation-delay: 0.3s; }

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
}
</style>
@endsection 