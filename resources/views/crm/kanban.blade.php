@extends('layouts.admin')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('title', 'Канбан-доска')

@section('content')
<div class="kanban-container">
    <!-- Заголовок канбан доски -->
    <div class="kanban-header">
        <h1 class="kanban-title">Канбан доска</h1>
        <p class="kanban-subtitle">Управление сделками и заявками в реальном времени</p>
    </div>

    <!-- Контейнер колонок - только горизонтально -->
    <div class="kanban-columns">
        <!-- Колонка: Черновик -->
        <div class="kanban-column" data-status="draft">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon draft"></div>
                    <span>Черновик</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['draft'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['draft'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->created_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot"></div>
                                <span class="status-text">Нет задач</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }} 〒</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: На проверке РОП -->
        <div class="kanban-column" data-status="pending_rop">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon pending-rop"></div>
                    <span>На проверке РОП</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['pending_rop'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['pending_rop'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot warning"></div>
                                <span class="status-text">На проверке</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Одобрен -->
        <div class="kanban-column" data-status="approved">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon approved"></div>
                    <span>Одобрен</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['approved'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['approved'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot"></div>
                                <span class="status-text">Одобрено</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Отклонен -->
        <div class="kanban-column" data-status="rejected">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon rejected"></div>
                    <span>Отклонен</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['rejected'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['rejected'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot error"></div>
                                <span class="status-text">Отклонено</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Приостановлен -->
        <div class="kanban-column" data-status="on_hold">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon on-hold"></div>
                    <span>Приостановлен</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['on_hold'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['on_hold'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot on-hold"></div>
                                <span class="status-text">Приостановлено</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: В производстве -->
        <div class="kanban-column" data-status="in_production">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon in-production"></div>
                    <span>В производстве</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['in_production'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['in_production'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot"></div>
                                <span class="status-text">В производстве</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Контроль качества -->
        <div class="kanban-column" data-status="quality_check">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon quality-check"></div>
                    <span>Контроль качества</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['quality_check'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['quality_check'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot quality-check"></div>
                                <span class="status-text">Контроль качества</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Готов к отгрузке -->
        <div class="kanban-column" data-status="ready">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon ready"></div>
                    <span>Готов к отгрузке</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['ready'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['ready'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot ready"></div>
                                <span class="status-text">Готов к отгрузке</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Отгружен -->
        <div class="kanban-column" data-status="shipped">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon shipped"></div>
                    <span>Отгружен</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['shipped'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['shipped'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot shipped"></div>
                                <span class="status-text">Отгружен</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Завершен -->
        <div class="kanban-column" data-status="completed">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon completed"></div>
                    <span>Завершен</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['completed'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['completed'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot completed"></div>
                                <span class="status-text">Завершен</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>

        <!-- Колонка: Возвращен на доработку -->
        <div class="kanban-column" data-status="returned">
            <div class="column-header">
                <div class="column-title">
                    <div class="status-icon returned"></div>
                    <span>Возвращен на доработку</span>
                </div>
                <div class="column-stats">{{ count($contractsByStatus['returned'] ?? []) }}</div>
            </div>
            
            <!-- Карточки сделок -->
            <div class="column-content">
                @forelse($contractsByStatus['returned'] ?? [] as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                        <div class="card-header">
                            <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                        <div class="card-meta">
                            <div class="card-time">{{ $contract->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="card-status">
                                <div class="status-dot returned"></div>
                                <span class="status-text">Возвращен на доработку</span>
                            </div>
                        </div>
                        
                        <div class="card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                            </div>
                            <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                        </div>
                        
                        <div class="card-manager">
                            <i class="fas fa-user manager-icon"></i>
                            <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                        
                        <div class="card-amount-bottom">〒{{ number_format($contract->order_total ?? 0, 0, ',', ' ') }}</div>
                        
                        <div class="card-actions">
                            <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-action" title="Позвонить">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="btn-action" title="Сообщение">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Пустая колонка без элементов -->
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
/* Современный дизайн канбан доски */
.kanban-container {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    min-height: 100vh;
    padding: 20px 10px 10px 300px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Заголовок канбан доски */
.kanban-header {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
}

.kanban-title {
    color: white;
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 16px 0;
}

.kanban-subtitle {
    color: #94a3b8;
    font-size: 16px;
    margin: 0;
}

/* Контейнер колонок - только горизонтально */
.kanban-columns {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    padding-bottom: 20px;
    min-height: 400px;
}

/* Колонка канбан - компактная */
.kanban-column {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    padding: 16px;
    backdrop-filter: blur(10px);
    min-width: 280px;
    max-width: 280px;
    flex-shrink: 0;
    height: fit-content;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.kanban-column:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

/* Заголовок колонки с цветными квадратиками */
.column-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
}

.column-title {
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    margin: 0;
}

.status-icon {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Цвета для разных статусов */
.status-icon.draft {
    background: #6b7280; /* Темно-серый */
}

.status-icon.pending-rop {
    background: #f59e0b; /* Оранжевый */
}

.status-icon.approved {
    background: #10b981; /* Зеленый */
}

.status-icon.rejected {
    background: #ef4444; /* Красный */
}

.status-icon.on-hold {
    background: #8b5cf6; /* Фиолетовый */
}

.status-icon.in-production {
    background: #3b82f6; /* Синий */
}

.status-icon.quality-check {
    background: #06b6d4; /* Голубой */
}

.status-icon.ready {
    background: #84cc16; /* Лаймовый */
}

.status-icon.shipped {
    background: #f97316; /* Темно-оранжевый */
}

.status-icon.completed {
    background: #059669; /* Темно-зеленый */
}

.status-icon.returned {
    background: #6b7280; /* Темно-серый */
}



.column-stats {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    border: 1px solid rgba(59, 130, 246, 0.3);
    min-width: 20px;
    text-align: center;
}





/* Карточка сделки - компактная */
.contract-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
    cursor: grab;
    animation: cardSlideIn 0.3s ease;
}

.contract-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.contract-card:active {
    cursor: grabbing;
}

/* Заголовок карточки - минималистичный */
.card-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px 0;
}

.card-number {
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    letter-spacing: 0.5px;
}



/* Время и статус */
.card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.card-time {
    color: #64748b;
    font-size: 10px;
    font-weight: 500;
}

.card-status {
    display: flex;
    align-items: center;
    gap: 4px;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10b981;
}

.status-dot.warning {
    background: #f59e0b;
}

.status-dot.error {
    background: #ef4444;
}

.status-dot.on-hold {
    background: #8b5cf6;
}

.status-dot.in-production {
    background: #3b82f6;
}

.status-dot.quality-check {
    background: #06b6d4;
}

.status-dot.ready {
    background: #84cc16;
}

.status-dot.shipped {
    background: #f97316;
}

.status-dot.completed {
    background: #059669;
}

.status-dot.returned {
    background: #6b7280;
}

.status-text {
    color: #64748b;
    font-size: 9px;
    font-weight: 500;
}

/* Прогресс - компактный */
.card-progress {
    margin-bottom: 8px;
}

.progress {
    background: #e2e8f0;
    border-radius: 6px;
    height: 4px;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
    height: 100%;
    border-radius: 6px;
    transition: width 0.3s ease;
}

.progress-text {
    color: #64748b;
    font-size: 9px;
    font-weight: 500;
    margin-top: 4px;
    display: block;
}

/* Менеджер */
.card-manager {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
}

.card-amount-bottom {
    color: #059669;
    font-weight: 700;
    font-size: 14px;
    text-align: left;
    margin-bottom: 8px;
    padding: 4px 8px;
    background: rgba(5, 150, 105, 0.1);
    border-radius: 6px;
}

.manager-icon {
    color: #64748b;
    font-size: 10px;
}

.manager-name {
    color: #475569;
    font-size: 10px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Действия - компактные */
.card-actions {
    display: flex;
    gap: 6px;
}

.btn-action {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 6px;
    color: #64748b;
    text-decoration: none;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    position: relative;
    overflow: hidden;
}

.btn-action::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-action:hover::before {
    left: 100%;
}

.btn-action:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
    transform: translateY(-1px);
}



/* Поиск - современный дизайн */
.kanban-search {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 16px 20px 16px 48px;
    color: white;
    font-size: 15px;
    font-weight: 500;
    width: 100%;
    max-width: 450px;
    margin-top: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    backdrop-filter: blur(10px);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.kanban-search::placeholder {
    color: rgba(148, 163, 184, 0.8);
    font-weight: 400;
}

.kanban-search:focus {
    outline: none;
    border-color: rgba(59, 130, 246, 0.6);
    background: rgba(255, 255, 255, 0.12);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    transform: translateY(-1px);
}

.kanban-search:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
}

.kanban-search::before {
    content: '🔍';
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    opacity: 0.7;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.kanban-search:focus::before {
    opacity: 1;
}

.kanban-search:focus {
    animation: searchPulse 2s infinite;
}

/* Индикатор результатов поиска */
.search-results-indicator {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    padding: 12px 16px;
    margin-top: 16px;
    backdrop-filter: blur(10px);
    animation: cardSlideIn 0.3s ease;
}

.search-count {
    color: #60a5fa;
    font-weight: 600;
    font-size: 14px;
}

.search-clear {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #f87171;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.search-clear:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.3);
    color: #fca5a5;
}



/* Уведомления */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #1e293b;
    color: white;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    z-index: 10000;
    animation: slideInRight 0.3s ease;
    max-width: 400px;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.notification-message {
    color: white;
    font-weight: 500;
    flex: 1;
}

.notification-close {
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    font-size: 18px;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.notification-close:hover {
    background: #f1f5f9;
    color: #475569;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes cardSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes searchPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
}

/* Sortable.js стили */
.sortable-ghost {
    opacity: 0.4;
    transform: rotate(5deg);
}

.sortable-chosen {
    transform: scale(1.02);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

.sortable-drag {
    opacity: 0.8;
    transform: rotate(5deg) scale(1.05);
}

/* Исправления для колонок */
.kanban-column .column-content {
    min-height: 150px;
}

/* Адаптивность для мобильных устройств */
@media (max-width: 640px) {
    .kanban-columns {
        gap: 12px;
        padding-bottom: 16px;
    }
    
    .kanban-container {
        padding: 16px;
    }
    
    .kanban-header {
        padding: 20px;
    }
    
    .kanban-title {
        font-size: 24px;
    }
    
    .kanban-subtitle {
        font-size: 14px;
    }
    
    .column-header {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
    
    .contract-card {
        padding: 10px;
    }
    
    .card-actions {
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .btn-action {
        min-width: 24px;
        height: 24px;
        padding: 4px;
    }
    
    .kanban-column {
        min-width: 250px;
        max-width: 250px;
    }
}

/* Статусные индикаторы */
.status-dot {
    position: relative;
}

.status-dot::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: inherit;
    opacity: 0.3;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.5);
        opacity: 0.1;
    }
    100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
}

/* Индикатор прокрутки */
.scroll-indicator {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(59, 130, 246, 0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.scroll-indicator.show {
    opacity: 1;
}


</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Современная канбан доска с Sortable.js
document.addEventListener('DOMContentLoaded', function() {
    initializeKanban();
});

// Индикатор прокрутки
function setupScrollIndicator() {
    const scrollContainer = document.querySelector('.kanban-columns');
    const indicator = document.createElement('div');
    indicator.className = 'scroll-indicator';
    indicator.innerHTML = '← Прокрутите для просмотра всех воронок →';
    document.body.appendChild(indicator);
    
    scrollContainer.addEventListener('scroll', function() {
        const isAtStart = this.scrollLeft === 0;
        const isAtEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth;
        
        if (!isAtStart && !isAtEnd) {
            indicator.innerHTML = '← → Прокрутите для просмотра всех воронок';
            indicator.classList.add('show');
        } else if (isAtStart) {
            indicator.innerHTML = '→ Прокрутите вправо для просмотра всех воронок';
            indicator.classList.add('show');
        } else if (isAtEnd) {
            indicator.innerHTML = '← Прокрутите влево для просмотра всех воронок';
            indicator.classList.add('show');
        }
        
        // Скрываем индикатор через 3 секунды
        setTimeout(() => {
            indicator.classList.remove('show');
        }, 3000);
    });
}

// Улучшенная инициализация
function initializeKanban() {
    // Инициализация Sortable.js для каждой колонки
    setupSortable();
    

    
    // Инициализация поиска
    setupSearchAndFilter();
    
    // Инициализация индикатора прокрутки
    setupScrollIndicator();
    
    // Автообновление каждые 30 секунд
    setInterval(updateKanbanData, 30000);
    
    // Плавная прокрутка к активной колонке
    setupSmoothScrolling();
}

// Плавная прокрутка к активной колонке
function setupSmoothScrolling() {
    const columns = document.querySelectorAll('.kanban-column');
    columns.forEach((column, index) => {
        column.addEventListener('click', function() {
            const container = document.querySelector('.kanban-columns');
            const columnLeft = column.offsetLeft;
            const containerWidth = container.clientWidth;
            const scrollLeft = columnLeft - (containerWidth / 2) + (column.offsetWidth / 2);
            
            container.scrollTo({
                left: scrollLeft,
                behavior: 'smooth'
            });
        });
    });
}

// Настройка Sortable.js
function setupSortable() {
    const columns = document.querySelectorAll('.kanban-column');
    
    columns.forEach(column => {
        const columnContent = column.querySelector('.column-content');
        if (columnContent) {
            new Sortable(columnContent, {
                group: 'contracts',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    const contractId = evt.item.dataset.contractId;
                    const newStatus = evt.to.closest('.kanban-column').dataset.status;
                    const oldStatus = evt.from.closest('.kanban-column').dataset.status;
                    
                    if (evt.from !== evt.to && newStatus && oldStatus !== newStatus) {
                        updateContractStatus(contractId, newStatus, evt.item);
                    }
                }
            });
        }
    });
}

// Обновление статуса договора
async function updateContractStatus(contractId, newStatus, card) {
    try {
        const url = `{{ route(Auth::user()->role . '.crm.update-status', ['contract' => ':contractId']) }}`.replace(':contractId', contractId);
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // Обновляем статистику колонок
                updateColumnStats();
                
                // Показываем уведомление
                showNotification('Статус обновлен успешно!', 'success');
            } else {
                throw new Error(data.error || 'Ошибка обновления статуса');
            }
        } else {
            throw new Error('Ошибка обновления статуса');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        showNotification('Ошибка обновления статуса: ' + error.message, 'error');
        
        // Возвращаем карточку на исходное место при ошибке
        setTimeout(() => {
            location.reload();
        }, 2000);
    }
}







// Обновление статистики колонок
function updateColumnStats() {
    const columns = document.querySelectorAll('.kanban-column');
    
    columns.forEach(column => {
        const status = column.dataset.status;
        const count = column.querySelectorAll('.contract-card').length;
        const statsElement = column.querySelector('.column-stats');
        
        if (statsElement) {
            statsElement.textContent = `${count} сделок`;
        }
    });
}

// Автообновление данных
async function updateKanbanData() {
    try {
        const url = `{{ route(Auth::user()->role . '.crm.kanban-data') }}`;
        const response = await fetch(url);
        if (response.ok) {
            const data = await response.json();
            // Обновляем данные без перезагрузки
            updateKanbanFromData(data);
        }
    } catch (error) {
        console.error('Ошибка автообновления:', error);
    }
}

// Обновление канбана из данных
function updateKanbanFromData(data) {
    // Здесь можно реализовать обновление без перезагрузки
    // Пока просто обновляем статистику
    updateColumnStats();
}

// Система уведомлений
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Автоматическое скрытие через 5 секунд
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Поиск и фильтрация
function setupSearchAndFilter() {
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Поиск по номеру договора, менеджеру, сумме...';
    searchInput.className = 'kanban-search';
    
    const header = document.querySelector('.kanban-header');
    header.appendChild(searchInput);
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        
        // Очищаем предыдущий таймаут
        clearTimeout(searchTimeout);
        
        // Сбрасываем подсветку
        resetHighlight();
        
        // Устанавливаем новый таймаут для поиска
        searchTimeout = setTimeout(() => {
            filterContracts(query);
        }, 300);
    });
    
    // Очистка поиска
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            resetHighlight();
            filterContracts('');
        }
    });
}

function filterContracts(query) {
    const cards = document.querySelectorAll('.contract-card');
    const columns = document.querySelectorAll('.kanban-column');
    let foundCount = 0;
    
    cards.forEach(card => {
        const contractNumber = card.querySelector('.card-number')?.textContent.toLowerCase() || '';
        const managerName = card.querySelector('.manager-name')?.textContent.toLowerCase() || '';
        const amount = card.querySelector('.card-amount-bottom')?.textContent.toLowerCase() || '';
        
        const matches = contractNumber.includes(query) || 
                       managerName.includes(query) || 
                       amount.includes(query);
        
        if (matches) {
            card.style.display = 'block';
            card.style.animation = 'cardSlideIn 0.3s ease';
            foundCount++;
            // Подсвечиваем найденный текст
            highlightText(card, query);
        } else {
            card.style.display = 'none';
        }
    });
    
    // Показываем/скрываем колонки в зависимости от наличия карточек
    columns.forEach(column => {
        const visibleCards = column.querySelectorAll('.contract-card[style*="display: block"]');
        if (visibleCards.length === 0 && query) {
            column.style.opacity = '0.3';
        } else {
            column.style.opacity = '1';
        }
    });
    
    // Показываем индикатор результатов поиска
    showSearchResults(query, foundCount);
}

// Подсветка найденного текста
function highlightText(card, query) {
    const elements = card.querySelectorAll('.card-number, .manager-name, .card-amount-bottom');
    elements.forEach(element => {
        const text = element.textContent;
        const regex = new RegExp(`(${query})`, 'gi');
        element.innerHTML = text.replace(regex, '<mark style="background: #fef3c7; color: #92400e; padding: 1px 2px; border-radius: 2px;">$1</mark>');
    });
}

// Сброс подсветки
function resetHighlight() {
    const marks = document.querySelectorAll('mark');
    marks.forEach(mark => {
        const parent = mark.parentElement;
        parent.innerHTML = parent.innerHTML.replace(/<mark[^>]*>(.*?)<\/mark>/g, '$1');
    });
}

// Показать результаты поиска
function showSearchResults(query, count) {
    // Удаляем предыдущий индикатор
    const existingIndicator = document.querySelector('.search-results-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    if (query && count > 0) {
        const indicator = document.createElement('div');
        indicator.className = 'search-results-indicator';
        indicator.innerHTML = `
            <span class="search-count">Найдено: ${count} сделок</span>
            <button class="search-clear" onclick="clearSearch()">Очистить</button>
        `;
        
        const header = document.querySelector('.kanban-header');
        header.appendChild(indicator);
    }
}

// Очистить поиск
function clearSearch() {
    const searchInput = document.querySelector('.kanban-search');
    if (searchInput) {
        searchInput.value = '';
        resetHighlight();
        filterContracts('');
        
        // Удаляем индикатор результатов
        const indicator = document.querySelector('.search-results-indicator');
        if (indicator) {
            indicator.remove();
        }
        
        // Восстанавливаем все колонки
        const columns = document.querySelectorAll('.kanban-column');
        columns.forEach(column => {
            column.style.opacity = '1';
        });
    }
}
</script>