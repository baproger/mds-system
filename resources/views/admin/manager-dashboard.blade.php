@extends('layouts.admin')

@section('title', 'Dashboard менеджера')

@section('content')
<div class="edit-branch-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Dashboard менеджера</h1>
                <p class="page-subtitle">Панель управления менеджера {{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>

    <!-- Статистика менеджера -->
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <span>Моя статистика</span>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['my_contracts'] }}</div>
                    <div class="stat-label">Мои договоры</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['contracts_pending'] }}</div>
                    <div class="stat-label">Ожидают</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['contracts_approved'] }}</div>
                    <div class="stat-label">Одобрены</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['revenue_this_month']) }} ₸</div>
                    <div class="stat-label">Доход за месяц</div>
                </div>
            </div>
        </div>
    </div>



    <!-- Последние договоры -->
    @if($recent_contracts->count() > 0)
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-list"></i>
            <span>Последние договоры</span>
        </div>
        
        <div class="personnel-section">
            @foreach($recent_contracts as $contract)
                <div class="personnel-item contract-item">
                    <div class="personnel-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="personnel-content">
                        <div class="personnel-title">Договор #{{ $contract->id }}</div>
                        <div class="personnel-list">
                            <span class="personnel-tag client-tag">{{ $contract->client_name }}</span>
                            <span class="personnel-tag amount-tag">{{ number_format($contract->order_total) }} ₸</span>
                            <span class="personnel-tag status-tag status-{{ $contract->status }}">
                                @if($contract->status === 'pending')
                                    Ожидает
                                @elseif($contract->status === 'approved')
                                    Одобрен
                                @elseif($contract->status === 'completed')
                                    Завершен
                                @elseif($contract->status === 'cancelled')
                                    Отменен
                                @else
                                    {{ ucfirst($contract->status) }}
                                @endif
                            </span>
                            <span class="personnel-tag date-tag">{{ $contract->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                    <div class="personnel-actions">
                        <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.show' : (Auth::user()->role === 'manager' ? 'manager.contracts.show' : 'rop.contracts.show'), $contract) }}" class="btn btn-sm btn-save" title="Просмотр">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.edit' : (Auth::user()->role === 'manager' ? 'manager.contracts.edit' : 'rop.contracts.edit'), $contract) }}" class="btn btn-sm btn-cancel" title="Редактировать">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Быстрые действия -->
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-bolt"></i>
            <span>Быстрые действия</span>
        </div>
        
        <div class="quick-actions">
            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.create' : (Auth::user()->role === 'manager' ? 'manager.contracts.create' : 'rop.contracts.create')) }}" class="quick-action-btn">
                <i class="fas fa-plus"></i>
                <span>Создать договор</span>
            </a>
            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.index' : (Auth::user()->role === 'manager' ? 'manager.contracts.index' : 'rop.contracts.index')) }}" class="quick-action-btn">
                <i class="fas fa-list"></i>
                <span>Мои договоры</span>
            </a>
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.personnel-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.personnel-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s ease;
}

.personnel-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.personnel-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.personnel-content {
    flex: 1;
}

.personnel-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.personnel-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.personnel-tag {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid;
}

.client-tag {
    background: #f0f9ff;
    color: #0369a1;
    border-color: #bae6fd;
}

.amount-tag {
    background: #f0fdf4;
    color: #166534;
    border-color: #bbf7d0;
}

.status-tag {
    background: #fef3c7;
    color: #d97706;
    border-color: #fcd34d;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
    border-color: #fcd34d;
}

.status-approved {
    background: #f0fdf4;
    color: #166534;
    border-color: #bbf7d0;
}

.status-completed {
    background: #f0f9ff;
    color: #0369a1;
    border-color: #bae6fd;
}

.status-cancelled {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}

.date-tag {
    background: #f3f4f6;
    color: #6b7280;
    border-color: #d1d5db;
}

.personnel-actions {
    display: flex;
    gap: 8px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

.btn-cancel {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
    color: #374151;
    text-decoration: none;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    color: #374151;
    font-weight: 600;
    transition: all 0.2s ease;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: #374151;
    text-decoration: none;
}

.quick-action-btn i {
    font-size: 20px;
    color: #667eea;
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

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
    
    .personnel-item {
        flex-direction: column;
        gap: 12px;
    }
    
    .personnel-actions {
        align-self: flex-end;
    }
}
</style>
@endsection 