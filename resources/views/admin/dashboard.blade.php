@extends('layouts.admin')

@section('title', 'Панель управления')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Панель управления</h1>
                            <p class="page-subtitle">Обзор системы управления договорами</p>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->role === 'admin')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Общая статистика</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_branches'] }}</div>
                                <div class="stat-label">Филиалов</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_users'] }}</div>
                                <div class="stat-label">Пользователей</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_sales_staff'] }}</div>
                                <div class="stat-label">Продавцов</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_contracts'] }}</div>
                                <div class="stat-label">Договоров</div>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif(Auth::user()->role === 'manager')
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
                                <div class="stat-number">{{ $stats['total_contracts'] ?? 0 }}</div>
                                <div class="stat-label">Мои договоры</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['total_revenue'] ?? 0) }} ₸</div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['contracts_this_month'] ?? 0 }}</div>
                                <div class="stat-label">За этот месяц</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['average_contract_value'] ?? 0) }} ₸</div>
                                <div class="stat-label">Средний договор</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->role === 'admin')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-building"></i>
                        <span>Статистика по филиалам</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($branches as $branch)
                            <div class="personnel-item branch-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">{{ $branch->name }}</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag branch-tag">{{ $branch->users_count }} пользователей</span>
                                        <span class="personnel-tag manager-tag">{{ $branch->managers_count ?? 0 }} продавцов</span>
                                        <span class="personnel-tag contract-tag">{{ $branch->contracts_count }} договоров</span>
                                        <span class="personnel-tag code-tag">{{ $branch->code }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @elseif(Auth::user()->role === 'manager')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-user"></i>
                        <span>Мой филиал</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($branches as $branch)
                            <div class="personnel-item branch-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">{{ $branch->name }}</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag contract-tag">{{ $branch->contracts_count }} моих договоров</span>
                                        <span class="personnel-tag code-tag">{{ $branch->code }}</span>
                                        @if(isset($stats['last_contract_date']) && $stats['last_contract_date'])
                                            <span class="personnel-tag date-tag">Последний: {{ $stats['last_contract_date']->format('d.m.Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(Auth::user()->role === 'admin')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-pie"></i>
                        <span>Детальная статистика</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_rop'] }}</div>
                                <div class="stat-label">РОП</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_managers'] }}</div>
                                <div class="stat-label">Менеджеров</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['revenue_this_year'] ?? 0) }} ₸</div>
                                <div class="stat-label">Общий доход</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_branches'] > 0 ? round($stats['total_contracts'] / $stats['total_branches'], 1) : 0 }}</div>
                                <div class="stat-label">Среднее договоров</div>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif(Auth::user()->role === 'manager')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-pie"></i>
                        <span>Мои показатели</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['contracts_this_year'] ?? 0 }}</div>
                                <div class="stat-label">За этот год</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['revenue_this_month'] ?? 0) }} ₸</div>
                                <div class="stat-label">Доход за месяц</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['revenue_this_year'] ?? 0) }} ₸</div>
                                <div class="stat-label">Доход за год</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ ($stats['total_contracts'] ?? 0) > 0 ? round((($stats['contracts_this_month'] ?? 0) / ($stats['total_contracts'] ?? 1)) * 100) : 0 }}%</div>
                                <div class="stat-label">Активность</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($recent_contracts->count() > 0)
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-clock"></i>
                        <span>{{ Auth::user()->role === 'manager' ? 'Мои последние договоры' : 'Последние договоры' }}</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($recent_contracts as $contract)
                            <div class="personnel-item contract-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">{{ $contract->contract_number }}</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag client-tag">{{ $contract->client }}</span>
                                        <span class="personnel-tag amount-tag">{{ number_format($contract->order_total) }} ₸</span>
                                        <span class="personnel-tag date-tag">{{ $contract->date->format('d.m.Y') }}</span>
                                        @if(Auth::user()->role === 'admin' && $contract->user)
                                            <span class="personnel-tag manager-tag">{{ $contract->user->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
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

.branch-item .personnel-icon {
    background: #eff6ff;
    color: #2563eb;
}

.contract-item .personnel-icon {
    background: #f0fdf4;
    color: #166534;
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

.branch-tag {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.manager-tag {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
}

.contract-tag {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.code-tag {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.client-tag {
    background: #f3e8ff;
    color: #7c3aed;
    border: 1px solid #c7d2fe;
}

.amount-tag {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.date-tag {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fcd34d;
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
.personnel-item:nth-child(3) { animation-delay: 0.3s; }
.personnel-item:nth-child(4) { animation-delay: 0.4s; }
.personnel-item:nth-child(5) { animation-delay: 0.5s; }
.personnel-item:nth-child(6) { animation-delay: 0.6s; }
.personnel-item:nth-child(7) { animation-delay: 0.7s; }
.personnel-item:nth-child(8) { animation-delay: 0.8s; }

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection 