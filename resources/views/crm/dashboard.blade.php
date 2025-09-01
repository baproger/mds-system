@extends('layouts.admin')

@section('title', 'CRM Дашборд')

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
                            <h1 class="page-title">CRM Дашборд</h1>
                            <p class="page-subtitle">Ключевые показатели и аналитика продаж</p>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Ключевые показатели</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $kpis['total_contracts'] ?? 0 }}</div>
                                <div class="stat-label">Всего договоров</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($kpis['total_revenue'] ?? 0, 0, ',', ' ') }} ₸</div>
                                <div class="stat-label">Общий доход</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($kpis['avg_contract_value'] ?? 0, 0, ',', ' ') }} ₸</div>
                                <div class="stat-label">Средний договор</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($kpis['conversion_rate'] ?? 0, 1) }}%</div>
                                <div class="stat-label">Конверсия</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-pie"></i>
                        <span>Распределение по статусам</span>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-content">
                            <div class="chart-canvas">
                                <canvas id="statusChart" width="400" height="200"></canvas>
                            </div>
                            <div class="chart-legend">
                                @foreach($statusStats as $status => $count)
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: {{ \App\Models\Contract::getStatusColor($status) }}"></div>
                                    <div class="legend-text">
                                        <span class="legend-label">{{ \App\Models\Contract::getStatusLabel($status) }}</span>
                                        <span class="legend-count">{{ $count }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-trophy"></i>
                        <span>Топ менеджеров</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($topManagers as $manager)
                        <div class="personnel-item manager-item">
                            <div class="personnel-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">{{ $manager->name }}</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag contract-tag">
                                        <i class="fas fa-file-contract tag-icon"></i>{{ $manager->contracts_count }} договоров
                                    </span>
                                    <span class="personnel-tag amount-tag">
                                        <i class="fas fa-money-bill-wave tag-icon"></i>{{ number_format($manager->total_revenue, 0, ',', ' ') }} ₸
                                    </span>
                                    <span class="personnel-tag avg-tag">
                                        <i class="fas fa-chart-line tag-icon"></i>{{ number_format($manager->avg_contract_value, 0, ',', ' ') }} ₸ средний
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-clock"></i>
                        <span>Последние договоры</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($recentContracts as $contract)
                        <div class="personnel-item contract-item">
                            <div class="personnel-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">#{{ $contract->contract_number }}</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag client-tag">
                                        <i class="fas fa-user tag-icon"></i>{{ $contract->client_name }}
                                    </span>
                                    <span class="personnel-tag amount-tag">
                                        <i class="fas fa-money-bill-wave tag-icon"></i>{{ number_format($contract->order_total, 0, ',', ' ') }} ₸
                                    </span>
                                    <span class="personnel-tag date-tag">
                                        <i class="fas fa-calendar-alt tag-icon"></i>{{ $contract->created_at->format('d.m.Y') }}
                                    </span>
                                    <span class="personnel-tag status-tag" style="background-color: {{ \App\Models\Contract::getStatusColor($contract->status) }}; color: white;">
                                        <i class="fas fa-circle tag-icon"></i>{{ \App\Models\Contract::getStatusLabel($contract->status) }}
                                    </span>
                                </div>
                                <div class="personnel-actions">
                                    <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
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



.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    font-size: 18px;
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

.chart-container {
    background: #fafafa;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #f3f4f6;
}

.chart-content {
    display: flex;
    gap: 24px;
    align-items: center;
}

.chart-canvas {
    flex: 2;
    min-height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-legend {
    flex: 1;
    background: white;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.legend-item:hover {
    background: #f9fafb;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    margin-right: 12px;
    flex-shrink: 0;
}

.legend-text {
    display: flex;
    justify-content: space-between;
    flex: 1;
    align-items: center;
}

.legend-label {
    font-weight: 500;
    color: #374151;
    font-size: 14px;
}

.legend-count {
    font-weight: 600;
    color: #6b7280;
    font-size: 14px;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 12px;
    min-width: 40px;
    text-align: center;
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
    gap: 6px;
    position: relative;
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

.manager-item .personnel-icon {
    background: #fef3c7;
    color: #92400e;
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
    font-size: 15px;
    color: #374151;
    margin-bottom: 8px;
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

.contract-tag {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.amount-tag {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.avg-tag {
    background: #fdf2f8;
    color: #be185d;
    border: 1px solid #fbcfe8;
}

.client-tag {
    background: #f0f9ff;
    color: #0369a1;
    border-color: #bae6fd;
}

.date-tag {
    background: #f3f4f6;
    color: #6b7280;
    border-color: #d1d5db;
}

.status-tag {
    border: none;
}

.personnel-actions {
    position: absolute;
    top: 16px;
    right: 16px;
}

.btn-action {
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 6px 8px;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-action:hover {
    background: #1ba4e9;
    color: white;
    border-color: #1ba4e9;
    transform: translateY(-1px);
}

.tag-icon {
    margin-right: 6px;
    opacity: 0.85;
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
.form-section:nth-child(4) { animation-delay: 0.4s; }

.personnel-item {
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
    
    .chart-content {
        flex-direction: column;
    }
    
    .chart-canvas {
        min-height: 200px;
    }
    
    .personnel-list {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    
    const data = {
        labels: {!! json_encode(array_map(function($status) { return \App\Models\Contract::getStatusLabel($status); }, array_keys($statusStats))) !!},
        datasets: [{
            data: {!! json_encode(array_values($statusStats)) !!},
            backgroundColor: {!! json_encode(array_map(function($status) { return \App\Models\Contract::getStatusColor($status); }, array_keys($statusStats))) !!},
            borderWidth: 2,
            borderColor: '#fff'
        }]
    };
    
    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '60%'
        }
    });
});
</script>
@endsection