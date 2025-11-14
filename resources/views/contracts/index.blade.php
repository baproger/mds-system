@extends("layouts.app")

@section("title", "Договоры")

@section("content")
<div class="contracts-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Договоры</h1>
                <p class="page-subtitle">Управление договорами на изготовление дверей</p>
            </div>
        </div>
        @if(Auth::check())
        <div class="header-actions">
            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.create' : (Auth::user()->role === 'manager' ? 'manager.contracts.create' : 'rop.contracts.create')) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Новый договор
            </a>
        </div>
        @endif
    </div>

    <!-- Статистика -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $contracts->total() }}</div>
                    <div class="stat-label">Всего договоров</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $contracts->where('date', '>=', now()->startOfMonth())->count() }}</div>
                    <div class="stat-label">За этот месяц</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($contracts->sum('order_total'), 0, ',', ' ') }} ₸</div>
                    <div class="stat-label">Общая сумма</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $contracts->unique('client')->count() }}</div>
                    <div class="stat-label">Уникальных клиентов</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Поиск и фильтры -->
    <div class="search-section">
        <div class="search-card">
            <div class="search-header">
                <i class="fas fa-search"></i>
                <span>Поиск договоров</span>
            </div>
            
            <form method="GET" action="{{ route('contracts.index') }}" class="search-form">
                <div class="search-grid">
                    <div class="search-group">
                        <label for="search" class="search-label">
                            <i class="fas fa-search"></i>
                            Поиск
                        </label>
                        <input type="text" name="search" id="search" class="search-input" 
                               placeholder="Поиск по номеру, клиенту или телефону..." 
                               value="{{ request("search") }}">
                    </div>
                    
                    <div class="search-group">
                        <label class="search-label">
                            <i class="fas fa-filter"></i>
                            Действия
                        </label>
                        <div class="search-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Найти
                            </button>
                            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Сброс
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Список договоров -->
    <div class="contracts-section">
        <div class="section-header">
            <i class="fas fa-list"></i>
            <span>Список договоров</span>
            <div class="section-actions">
                <span class="contracts-count">{{ $contracts->total() }} договоров</span>
            </div>
        </div>
        
        @if($contracts->count() > 0)
            <div class="contracts-list">
                @foreach($contracts as $contract)
                    <div class="contract-item">
                        <div class="contract-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="contract-content">
                            <div class="contract-title">{{ $contract->contract_number }}</div>
                            <div class="contract-details">
                                <span class="contract-tag client-tag">{{ $contract->client }}</span>
                                <span class="contract-tag date-tag">{{ $contract->date->format('d.m.Y') }}</span>
                                <span class="contract-tag amount-tag">{{ number_format($contract->order_total) }} ₸</span>
                                @if($contract->branch)
                                    <span class="contract-tag branch-tag">{{ $contract->branch->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="contract-actions">
                            <a href="{{ route(Auth::user() && Auth::user()->role === 'admin' ? 'admin.contracts.show' : (Auth::user()->role === 'manager' ? 'manager.contracts.show' : 'rop.contracts.show'), $contract) }}" class="btn btn-sm btn-secondary" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(Auth::check())
                            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.edit' : (Auth::user()->role === 'manager' ? 'manager.contracts.edit' : 'rop.contracts.edit'), $contract) }}" class="btn btn-sm btn-primary" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            <a href="{{ route('contracts.print', $contract) }}" target="_blank" class="btn btn-sm btn-info" title="Печать">
                                <i class="fas fa-print"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Пагинация -->
            @if($contracts->hasPages())
                <div class="pagination-container">
                    {{ $contracts->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-file-contract"></i>
                <p>Договоры не найдены</p>
            </div>
        @endif
    </div>
</div>

<style>
.contracts-container {
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

.header-actions {
    display: flex;
    gap: 12px;
}

.stats-section {
    margin-bottom: 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--bg-tertiary);
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
    font-size: 24px;
    font-weight: 700;
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

.search-section {
    margin-bottom: 32px;
}

.search-card {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--bg-tertiary);
}

.search-header {
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

.search-header i {
    color: #1ba4e9;
    font-size: 18px;
}

.search-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    align-items: end;
}

.search-group {
    position: relative;
}

.search-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.search-label i {
    color: var(--text-secondary);
    font-size: 14px;
}

.search-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #fafafa;
}

.search-input:focus {
    outline: none;
    border-color: #1ba4e9;
    background: var(--bg-card);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-actions {
    display: flex;
    gap: 12px;
}

.contracts-section {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--bg-tertiary);
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
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

.section-actions {
    display: flex;
    gap: 12px;
}

.contracts-count {
    color: var(--text-secondary);
    font-size: 14px;
}

.contracts-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.contract-item {
    display: flex;
    align-items: flex-start;
    padding: 16px;
    background: #fafafa;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.contract-item:hover {
    background: #f8f9fa;
    border-color: #e9ecef;
    transform: translateY(-1px);
}

.contract-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
    background: #f0fdf4;
    color: #166534;
}

.contract-content {
    flex: 1;
    min-width: 0;
}

.contract-title {
    font-weight: 600;
    font-size: 16px;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.contract-details {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.contract-tag {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    transition: all 0.2s ease;
}

.client-tag {
    background: #f3e8ff;
    color: #0284c7;
    border: 1px solid #c7d2fe;
}

.phone-tag {
    background: #f0f9ff;
    color: #0369a1;
    border: 1px solid #bae6fd;
}

.date-tag {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fcd34d;
}

.amount-tag {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.branch-tag {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.contract-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 12px;
}

.btn-primary {
    background: #e0f2fe;
    color: #1ba4e9;
    color: #1ba4e9;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(27, 164, 233, 0.3);
}

.btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: var(--border-color);
    transform: translateY(-1px);
}

.btn-info {
    background: #eff6ff;
    color: #1e40af;
}

.btn-info:hover {
    background: #dbeafe;
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 16px;
    margin: 0;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 24px;
}

/* Анимации */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.stats-section {
    animation: fadeIn 0.3s ease-out;
}

.search-section {
    animation: fadeIn 0.3s ease-out;
    animation-delay: 0.1s;
}

.contracts-section {
    animation: fadeIn 0.3s ease-out;
    animation-delay: 0.2s;
}

.contract-item {
    animation: fadeIn 0.3s ease-out;
}

/* Адаптивность */
@media (max-width: 768px) {
    .contracts-container {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .search-grid {
        grid-template-columns: 1fr;
    }
    
    .search-actions {
        flex-direction: column;
    }
    
    .contract-item {
        flex-direction: column;
        gap: 12px;
    }
    
    .contract-actions {
        align-self: flex-end;
    }
}
</style>
@endsection
