@extends('layouts.admin')

@section('title', 'Добавить филиал')

@section('content')
<div class="edit-branch-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Добавить филиал</h1>
                <p class="page-subtitle">Создание нового филиала в системе</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад к списку
            </a>
        </div>
    </div>

    <!-- Форма создания филиала -->
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-info-circle"></i>
            <span>Информация о филиале</span>
        </div>

        <form method="POST" action="{{ route('admin.branches.store') }}" class="form-content">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-building"></i>
                        Название филиала
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    <small class="form-text">Введите полное название филиала</small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="code" class="form-label">
                        <i class="fas fa-code"></i>
                        Код филиала
                    </label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                           id="code" name="code" value="{{ old('code') }}" required>
                    <small class="form-text">Уникальный код филиала (например: SHY-PP, ALA-TST)</small>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contract_counter" class="form-label">
                        <i class="fas fa-hashtag"></i>
                        Начальный номер договоров
                    </label>
                    <input type="number" class="form-control @error('contract_counter') is-invalid @enderror" 
                           id="contract_counter" name="contract_counter" value="{{ old('contract_counter', 1) }}" 
                           min="1" required>
                    <small class="form-text">Номер, с которого начинается нумерация договоров в этом филиале</small>
                    @error('contract_counter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Информационная карточка -->
            <div class="info-section">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-title">Диапазоны номеров договоров</div>
                        <div class="info-text">
                            <div class="range-grid">
                                <div class="range-item">
                                    <span class="range-code">SHY-PP</span>
                                    <span class="range-numbers">20000-29999</span>
                                </div>
                                <div class="range-item">
                                    <span class="range-code">SHY-RZ</span>
                                    <span class="range-numbers">30000-39999</span>
                                </div>
                                <div class="range-item">
                                    <span class="range-code">AKT</span>
                                    <span class="range-numbers">40000-49999</span>
                                </div>
                                <div class="range-item">
                                    <span class="range-code">ALA-TST</span>
                                    <span class="range-numbers">50000-57999</span>
                                </div>
                                <div class="range-item">
                                    <span class="range-code">ALA-SC</span>
                                    <span class="range-numbers">58000-59999</span>
                                </div>
                                <div class="range-item">
                                    <span class="range-code">TRZ</span>
                                    <span class="range-numbers">100000-119999</span>
                                </div>
                                <div class="range-item">
                                    <span class="range-code">ATR</span>
                                    <span class="range-numbers">120000-139999</span>
                                </div>
                                <div class="range-item">
                                    <span class="range-code">TAS</span>
                                    <span class="range-numbers">60000-69999</span>
                                </div>
                            </div>
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
                    Создать филиал
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.edit-branch-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
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

.header-actions {
    display: flex;
    gap: 12px;
}

.form-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
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

.form-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
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
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
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

.info-section {
    margin-top: 16px;
}

.info-card {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    display: flex;
    gap: 16px;
}

.info-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-title {
    font-weight: 600;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
}

.info-text {
    color: #6b7280;
    font-size: 13px;
}

.range-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-top: 12px;
}

.range-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.range-code {
    font-weight: 600;
    color: #374151;
    font-size: 12px;
}

.range-numbers {
    color: #6b7280;
    font-size: 12px;
    font-family: monospace;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #f3f4f6;
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

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

/* Анимации */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.edit-branch-container {
    animation: fadeIn 0.3s ease-out;
}

.form-section {
    animation: fadeIn 0.3s ease-out;
    animation-delay: 0.1s;
}

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .range-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection 