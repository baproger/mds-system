@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="title-section">
                <div class="title-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="title-text">
                    <h1>История изменений</h1>
                    <p>Договор №{{ $contract->contract_number }}</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.show' : (Auth::user()->role === 'rop' ? 'rop.contracts.show' : 'manager.contracts.show'), $contract) }}" class="btn-modern btn-view">
                    <i class="fas fa-eye"></i>
                    <span>Просмотр договора</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Информация о договоре -->
    <div class="info-card">
        <div class="info-header">
            <div class="info-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <h2>Информация о договоре</h2>
        </div>
        <div class="info-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Клиент</label>
                    <span>{{ $contract->client }}</span>
                </div>
                <div class="info-item">
                    <label>Менеджер</label>
                    <span>{{ $contract->manager }}</span>
                </div>
                <div class="info-item">
                    <label>Филиал</label>
                    <span>{{ $contract->branch->name ?? 'Не указан' }}</span>
                </div>
                <div class="info-item">
                    <label>Текущий статус</label>
                    <div class="status-wrapper">
                        <x-contract-status :contract="$contract" />
                    </div>
                </div>
                <div class="info-item">
                    <label>Дата создания</label>
                    <span>{{ $contract->created_at->format('d.m.Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <label>Последнее обновление</label>
                    <span>{{ $contract->updated_at->format('d.m.Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline изменений -->
    <div class="timeline-card">
        <div class="timeline-header">
            <div class="timeline-icon">
                <i class="fas fa-stream"></i>
            </div>
            <h2>Timeline изменений</h2>
        </div>
        
        @if($approvals->count() > 0 || $changes->count() > 0)
            <div class="timeline-modern">
                @php
                    $allEvents = collect();
                    
                    // Добавляем одобрения
                    foreach($approvals as $approval) {
                        $allEvents->push([
                            'type' => 'approval',
                            'data' => $approval,
                            'date' => $approval->created_at
                        ]);
                    }
                    
                    // Добавляем изменения
                    foreach($changes as $version => $versionChanges) {
                        foreach($versionChanges as $change) {
                            $allEvents->push([
                                'type' => 'change',
                                'data' => $change,
                                'date' => $change->changed_at
                            ]);
                        }
                    }
                    
                    // Сортируем по дате
                    $allEvents = $allEvents->sortByDesc('date');
                @endphp

                @foreach($allEvents as $event)
                    <div class="timeline-item-modern {{ $event['type'] === 'approval' ? 'approval' : 'change' }}">
                        <div class="timeline-marker-modern">
                            <div class="marker-icon">
                                <i class="fas {{ $event['type'] === 'approval' ? $event['data']->action_icon : 'fa-edit' }}"></i>
                            </div>
                            <div class="marker-line"></div>
                        </div>
                        
                        <div class="timeline-content-modern">
                            <div class="content-header">
                                <div class="content-title">
                                    @if($event['type'] === 'approval')
                                        {{ $event['data']->full_description }}
                                    @else
                                        Изменено поле "{{ $event['data']->field_label }}"
                                    @endif
                                </div>
                                <div class="content-time">
                                    {{ $event['date']->format('d.m.Y H:i') }}
                                </div>
                            </div>
                            
                            @if($event['type'] === 'approval')
                                <div class="content-body approval">
                                    <div class="action-badge">
                                        <span class="badge-modern {{ $event['data']->action_color }}">
                                            {{ $event['data']->action_label }}
                                        </span>
                                    </div>
                                    @if($event['data']->comment)
                                        <div class="comment-section">
                                            <p>{{ $event['data']->comment }}</p>
                                        </div>
                                    @endif
                                    <div class="user-info">
                                        <i class="fas fa-user-circle"></i>
                                        <span>{{ $event['data']->createdBy->name ?? 'Неизвестный пользователь' }}</span>
                                        <span class="role-badge">({{ $event['data']->from_role }})</span>
                                    </div>
                                </div>
                            @else
                                <div class="content-body change">
                                    <div class="change-comparison">
                                        <div class="change-old">
                                            <span class="change-label">Было</span>
                                            <span class="change-value">{{ $event['data']->formatted_old_value }}</span>
                                        </div>
                                        <div class="change-arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        <div class="change-new">
                                            <span class="change-label">Стало</span>
                                            <span class="change-value">{{ $event['data']->formatted_new_value }}</span>
                                        </div>
                                    </div>
                                    <div class="user-info">
                                        <i class="fas fa-user-circle"></i>
                                        <span>{{ $event['data']->user->name ?? 'Неизвестный пользователь' }}</span>
                                        <span class="role-badge">({{ $event['data']->role }})</span>
                                        <span class="version-badge">v{{ $event['data']->version_to }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3>История изменений пуста</h3>
                <p>По данному договору пока не было внесено изменений</p>
            </div>
        @endif
    </div>
</div>

<style>
/* Современный минималистичный дизайн */
.page-header-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #1ba4e9;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.title-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.title-text h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 600;
    letter-spacing: -0.5px;
}

.title-text p {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.15);
    color: #1ba4e9;
    text-decoration: none;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-modern:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: #1ba4e9;
    text-decoration: none;
}

/* Карточки */
.info-card, .timeline-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    overflow: hidden;
    border: 1px solid var(--bg-secondary);
}

.info-header, .timeline-header {
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-icon, .timeline-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1ba4e9;
    font-size: 1.1rem;
}

.info-header h2, .timeline-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
}

.info-content {
    padding: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    font-size: 1rem;
    color: #1e293b;
    font-weight: 500;
}

.status-wrapper {
    display: inline-block;
}

/* Timeline */
.timeline-modern {
    padding: 1.5rem;
    position: relative;
}

.timeline-item-modern {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    position: relative;
}

.timeline-marker-modern {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}

.marker-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1ba4e9;
    font-size: 1.1rem;
    position: relative;
    z-index: 2;
}

.timeline-item-modern.approval .marker-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.timeline-item-modern.change .marker-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.marker-line {
    width: 2px;
    height: 100%;
    background: var(--border-color);
    margin-top: 0.5rem;
}

.timeline-content-modern {
    flex: 1;
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.timeline-content-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.content-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.4;
}

.content-time {
    font-size: 0.875rem;
    color: #64748b;
    background: var(--bg-card);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    border: 1px solid var(--border-color);
}

.content-body {
    font-size: 0.875rem;
}

/* Approval content */
.content-body.approval .action-badge {
    margin-bottom: 1rem;
}

.badge-modern {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-modern.success {
    background: #d1fae5;
    color: #065f46;
}

.badge-modern.danger {
    background: #fee2e2;
    color: #991b1b;
}

.badge-modern.warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-modern.info {
    background: #dbeafe;
    color: #1e40af;
}

.comment-section {
    background: var(--bg-card);
    padding: 0.75rem;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
    margin-bottom: 1rem;
}

.comment-section p {
    margin: 0;
    color: var(--text-primary);
    font-style: italic;
}

/* Change content */
.change-comparison {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    background: var(--bg-card);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.change-old, .change-new {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.change-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.change-value {
    font-size: 0.875rem;
    color: #1e293b;
    font-weight: 500;
    padding: 0.5rem;
    border-radius: 6px;
}

.change-old .change-value {
    background: #fef2f2;
    color: #dc2626;
}

.change-new .change-value {
    background: #f0fdf4;
    color: #16a34a;
}

.change-arrow {
    color: #94a3b8;
    font-size: 0.875rem;
}

/* User info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.75rem;
}

.user-info i {
    color: #94a3b8;
}

.role-badge {
    background: var(--bg-secondary);
    color: #475569;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.625rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.version-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.625rem;
    font-weight: 500;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
    color: #64748b;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 500;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .title-section {
        flex-direction: column;
        text-align: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .timeline-item-modern {
        flex-direction: column;
        gap: 1rem;
    }
    
    .timeline-marker-modern {
        align-self: flex-start;
    }
    
    .change-comparison {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .change-arrow {
        transform: rotate(90deg);
    }
}
</style>
@endsection
