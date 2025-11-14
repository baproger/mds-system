@extends('layouts.admin')

@section('title', 'CRM Обзор')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">CRM Система</h1>
                            <p class="page-subtitle">Профессиональное управление договорами и продажами</p>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>CRM Модули</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-trello"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">Канбан</div>
                                <div class="stat-label">Визуальное управление договорами</div>
                                <div class="stat-action">
                                    <a href="{{ route(Auth::user()->role . '.crm.kanban') }}" class="btn-module">
                                    <i class="fas fa-arrow-right"></i> Открыть
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">Дашборд</div>
                                <div class="stat-label">Ключевые метрики и KPI</div>
                                <div class="stat-action">
                                    <a href="{{ route(Auth::user()->role . '.crm.dashboard') }}" class="btn-module">
                                        <i class="fas fa-arrow-right"></i> Открыть
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Текущая статистика</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ \App\Models\Contract::count() }}</div>
                                <div class="stat-label">Всего договоров</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ \App\Models\Contract::where('status', 'completed')->count() }}</div>
                                <div class="stat-label">Завершенных</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ \App\Models\Contract::where('status', 'in_production')->count() }}</div>
                                <div class="stat-label">В производстве</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format(\App\Models\Contract::sum('order_total'), 0, ',', ' ') }} ₸</div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-bolt"></i>
                        <span>Быстрые действия</span>
                    </div>
                    
                    <div class="quick-actions-grid">
                        <a href="{{ route(Auth::user()->role . '.contracts.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Создать договор</div>
                                <div class="quick-action-subtitle">Новый договор клиента</div>
                            </div>
                        </a>
                        
                        <a href="{{ route(Auth::user()->role . '.contracts.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Все договоры</div>
                                <div class="quick-action-subtitle">Полный список договоров</div>
                            </div>
                        </a>
                        
                        <a href="{{ route(Auth::user()->role . '.calculator.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Калькулятор</div>
                                <div class="quick-action-subtitle">Расчет стоимости</div>
                            </div>
                        </a>
                    </div>
                </div>
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
    color: #1ba4e9;
    font-size: 18px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
    font-size: 18px;
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
    margin-bottom: 8px;
}

.stat-action {
    margin-top: 8px;
}

.btn-module {
    background: #e0f2fe;
    color: #1ba4e9;
    color: #1ba4e9;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.btn-module:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(27, 164, 233, 0.3);
    color: #1ba4e9;
    text-decoration: none;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.quick-action-card {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-secondary) 100%);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.quick-action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    border-color: #1ba4e9;
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    background: #e0f2fe;
    color: #1ba4e9;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1ba4e9;
    font-size: 18px;
    flex-shrink: 0;
}

.quick-action-content {
    flex: 1;
}

.quick-action-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.quick-action-subtitle {
    font-size: 12px;
    color: var(--text-secondary);
    font-weight: 500;
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



.stat-card {
    animation: fadeIn 0.3s ease-out;
}

.quick-action-card {
    animation: fadeIn 0.3s ease-out;
}

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .quick-action-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection