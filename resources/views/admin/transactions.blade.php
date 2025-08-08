@extends('layouts.admin')

@section('page-title', 'Договоры')

@section('content')
<div class="dashboard-grid">
    <!-- Page Header -->
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Договоры</h1>
            <p class="dashboard-subtitle">Управление всеми договорами системы</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-header">
                <div class="summary-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">₸ {{ number_format($totalIncome, 0, ',', ' ') }}</div>
            <div class="summary-label">Общий доход</div>
            <div class="summary-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+15% с прошлого месяца</span>
            </div>
        </div>

        <div class="summary-card success">
            <div class="summary-header">
                <div class="summary-icon success">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">₸ {{ number_format($totalExpenses, 0, ',', ' ') }}</div>
            <div class="summary-label">Общие расходы</div>
            <div class="summary-trend negative">
                <i class="fas fa-arrow-down"></i>
                <span>-8% с прошлого месяца</span>
            </div>
        </div>

        <div class="summary-card warning">
            <div class="summary-header">
                <div class="summary-icon warning">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">{{ $totalTransactions }}</div>
            <div class="summary-label">Всего договоров</div>
            <div class="summary-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12% с прошлого месяца</span>
            </div>
        </div>

        <div class="summary-card danger">
            <div class="summary-header">
                <div class="summary-icon danger">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="summary-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="summary-value">{{ $pendingTransactions }}</div>
            <div class="summary-label">Ожидающие</div>
            <div class="summary-trend negative">
                <i class="fas fa-arrow-down"></i>
                <span>-5% с прошлого месяца</span>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="transactions-section">
        <div class="transactions-header">
            <h2 class="transactions-title">Все договоры</h2>
            <div class="transactions-controls">
                <div class="transactions-search">
                    <input type="text" placeholder="Поиск договоров...">
                    <i class="fas fa-search"></i>
                </div>
                <button class="filter-btn">
                    <i class="fas fa-filter"></i>
                    Фильтр
                </button>
                <button class="add-card-btn">
                    <i class="fas fa-plus"></i>
                    Новый договор
                </button>
            </div>
        </div>
        
        <table class="modern-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="table-checkbox">
                    </th>
                    <th>
                        Номер договора
                        <i class="fas fa-sort"></i>
                    </th>
                    <th>
                        Клиент
                        <i class="fas fa-sort"></i>
                    </th>
                    <th>
                        Email
                        <i class="fas fa-sort"></i>
                    </th>
                    <th>
                        Дата
                        <i class="fas fa-sort"></i>
                    </th>
                    <th>
                        Сумма
                        <i class="fas fa-sort"></i>
                    </th>
                    <th>
                        Статус
                        <i class="fas fa-sort"></i>
                    </th>
                    <th>
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>
                        <input type="checkbox" class="table-checkbox">
                    </td>
                    <td>
                        <strong>{{ $transaction->contract_number }}</strong>
                    </td>
                    <td>
                        <div class="customer-info">
                            <div class="customer-avatar">
                                {{ strtoupper(substr($transaction->client, 0, 1)) }}
                            </div>
                            <div class="customer-details">
                                <h4>{{ $transaction->client }}</h4>
                                <p>{{ $transaction->phone }}</p>
                            </div>
                        </div>
                    </td>
                    <td>{{ $transaction->client }}@example.com</td>
                    <td>{{ $transaction->date->format('d M Y') }}</td>
                    <td>
                        <strong>₸ {{ number_format($transaction->order_total, 0, ',', ' ') }}</strong>
                    </td>
                    <td>
                        <span class="deal-stage {{ $transaction->status === 'approved' ? 'success' : 'pending' }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: var(--space-8); color: var(--gray-500);">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: var(--space-4); display: block;"></i>
                        <p>Нет договоров для отображения</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div style="display: flex; justify-content: center; margin-top: var(--space-6);">
            {{ $transactions->links() }}
        </div>
        @endif
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