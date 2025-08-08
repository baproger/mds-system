@extends('layouts.admin')

@section('page-title', 'Задачи')

@section('content')
<div class="dashboard-grid">
    <!-- Page Header -->
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Задачи</h1>
            <p class="dashboard-subtitle">Управление задачами и целями компании</p>
        </div>
        <button class="add-card-btn">
            <i class="fas fa-plus"></i>
            Новая задача
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-header">
                <div class="summary-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">{{ $totalGoals }}</div>
            <div class="summary-label">Всего задач</div>
            <div class="summary-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+3 новые задачи</span>
            </div>
        </div>

        <div class="summary-card success">
            <div class="summary-header">
                <div class="summary-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">{{ $completedGoals }}</div>
            <div class="summary-label">Завершенные</div>
            <div class="summary-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+25% с прошлого месяца</span>
            </div>
        </div>

        <div class="summary-card warning">
            <div class="summary-header">
                <div class="summary-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">{{ $activeGoals }}</div>
            <div class="summary-label">Активные</div>
            <div class="summary-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+8% с прошлого месяца</span>
            </div>
        </div>

        <div class="summary-card danger">
            <div class="summary-header">
                <div class="summary-icon danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">{{ $overdueGoals }}</div>
            <div class="summary-label">Просроченные</div>
            <div class="summary-trend negative">
                <i class="fas fa-arrow-down"></i>
                <span>-12% с прошлого месяца</span>
            </div>
        </div>
    </div>

    <!-- Goals Grid -->
    <div class="cards-section">
        <div class="cards-header">
            <h2 class="cards-title">Мои задачи</h2>
            <div class="transactions-controls">
                <div class="transactions-search">
                    <input type="text" placeholder="Поиск задач...">
                    <i class="fas fa-search"></i>
                </div>
                <button class="filter-btn">
                    <i class="fas fa-filter"></i>
                    Фильтр
                </button>
            </div>
        </div>
        
        <div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));">
            @forelse($goals as $goal)
            <div class="card-item" style="flex-direction: column; align-items: stretch; gap: var(--space-4);">
                <div class="card-info" style="justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: var(--space-4);">
                        <div class="card-logo" style="background: {{ $goal['color'] }};">
                            <i class="fas {{ $goal['icon'] }}"></i>
                        </div>
                        <div class="card-details">
                            <h4>{{ $goal['title'] }}</h4>
                            <p>{{ $goal['description'] }}</p>
                        </div>
                    </div>
                    <div class="card-status {{ $goal['status'] === 'completed' ? 'active' : ($goal['status'] === 'overdue' ? 'disabled' : 'pending') }}">
                        {{ ucfirst($goal['status']) }}
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 1.25rem; font-weight: 600; color: var(--gray-800);">
                            ₸ {{ number_format($goal['current_amount'], 0, ',', ' ') }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--gray-500);">
                            из ₸ {{ number_format($goal['target_amount'], 0, ',', ' ') }}
                        </div>
                    </div>
                    
                    <div style="text-align: right;">
                        <div style="font-size: 1rem; font-weight: 600; color: var(--gray-800);">
                            {{ $goal['progress'] }}%
                        </div>
                        <div style="width: 100px; height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $goal['progress'] }}%; height: 100%; background: {{ $goal['color'] }}; transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--gray-500);">
                    <span>Создано: {{ $goal['created_at'] }}</span>
                    <span>Дедлайн: {{ $goal['deadline'] }}</span>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: var(--space-8); color: var(--gray-500);">
                <i class="fas fa-bullseye" style="font-size: 3rem; margin-bottom: var(--space-4); display: block; opacity: 0.5;"></i>
                <p>Нет задач для отображения</p>
                <button class="add-card-btn" style="margin-top: var(--space-4);">
                    <i class="fas fa-plus"></i>
                    Создать первую задачу
                </button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Progress Overview -->
    <div class="monitoring-section">
        <div class="monitoring-header">
            <h2 class="monitoring-title">Обзор прогресса</h2>
            <div class="monitoring-controls">
                <select class="period-selector">
                    <option>Этот месяц</option>
                    <option>Этот квартал</option>
                    <option>Этот год</option>
                </select>
            </div>
        </div>
        
        <div class="chart-container">
            <div style="text-align: center;">
                <i class="fas fa-chart-pie" style="font-size: 3rem; color: var(--gray-400); margin-bottom: var(--space-4);"></i>
                <p>График прогресса задач</p>
                <p style="font-size: 0.75rem; color: var(--gray-500);">Здесь будет интерактивный график</p>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    padding: var(--space-4);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-6);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    font-size: 0.875rem;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert i {
    font-size: 1rem;
}

.fade-out {
    animation: fadeOut 0.3s ease forwards;
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-10px); }
}
</style>
@endsection 