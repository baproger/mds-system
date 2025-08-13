@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-history header-icon"></i>
                История изменений
            </h1>
            <p class="page-subtitle">Договор №{{ $contract->contract_number }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.show' : (Auth::user()->role === 'manager' ? 'manager.contracts.show' : (Auth::user()->role === 'rop' ? 'rop.contracts.show' : 'accountant.contracts.show')), $contract) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                Назад к договору
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i>
                        Информация о договоре
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Клиент:</strong> {{ $contract->client }}</p>
                            <p><strong>Менеджер:</strong> {{ $contract->manager }}</p>
                            <p><strong>Филиал:</strong> {{ $contract->branch->name ?? 'Не указан' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Текущий статус:</strong> 
                                <x-contract-status :contract="$contract" />
                            </p>
                            <p><strong>Дата создания:</strong> {{ $contract->created_at->format('d.m.Y H:i') }}</p>
                            <p><strong>Последнее обновление:</strong> {{ $contract->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-timeline"></i>
                        Timeline изменений
                    </h5>
                </div>
                <div class="card-body">
                    @if($approvals->count() > 0 || $changes->count() > 0)
                        <div class="timeline">
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
                                <div class="timeline-item">
                                    <div class="timeline-marker {{ $event['type'] === 'approval' ? 'approval' : 'change' }}">
                                        <i class="fas {{ $event['type'] === 'approval' ? $event['data']->action_icon : 'fa-edit' }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <h6 class="timeline-title">
                                                @if($event['type'] === 'approval')
                                                    {{ $event['data']->full_description }}
                                                @else
                                                    Изменено поле "{{ $event['data']->field_label }}"
                                                @endif
                                            </h6>
                                            <span class="timeline-date">
                                                {{ $event['date']->format('d.m.Y H:i') }}
                                            </span>
                                        </div>
                                        
                                        @if($event['type'] === 'approval')
                                            <div class="timeline-body">
                                                <p class="mb-2">
                                                    <strong>Действие:</strong> 
                                                    <span class="badge bg-{{ $event['data']->action_color }}">
                                                        {{ $event['data']->action_label }}
                                                    </span>
                                                </p>
                                                @if($event['data']->comment)
                                                    <p class="mb-0">
                                                        <strong>Комментарий:</strong> {{ $event['data']->comment }}
                                                    </p>
                                                @endif
                                                <p class="mb-0 text-muted">
                                                    <small>
                                                        <i class="fas fa-user"></i>
                                                        {{ $event['data']->createdBy->name ?? 'Неизвестный пользователь' }}
                                                        ({{ $event['data']->from_role }})
                                                    </small>
                                                </p>
                                            </div>
                                        @else
                                            <div class="timeline-body">
                                                <div class="change-details">
                                                    <div class="change-old">
                                                        <strong>Было:</strong> {{ $event['data']->formatted_old_value }}
                                                    </div>
                                                    <div class="change-new">
                                                        <strong>Стало:</strong> {{ $event['data']->formatted_new_value }}
                                                    </div>
                                                </div>
                                                <p class="mb-0 text-muted">
                                                    <small>
                                                        <i class="fas fa-user"></i>
                                                        {{ $event['data']->user->name ?? 'Неизвестный пользователь' }}
                                                        ({{ $event['data']->role }})
                                                        • Версия {{ $event['data']->version_to }}
                                                    </small>
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">История изменений пуста</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-marker.approval {
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
}

.timeline-marker.change {
    background: #6b7280;
}

.timeline-content {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-left: 20px;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.timeline-title {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.timeline-date {
    font-size: 12px;
    color: #6b7280;
    white-space: nowrap;
    margin-left: 12px;
}

.timeline-body {
    font-size: 13px;
}

.change-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 8px;
}

.change-old, .change-new {
    padding: 8px;
    border-radius: 4px;
    font-size: 12px;
}

.change-old {
    background: #fee2e2;
    color: #991b1b;
}

.change-new {
    background: #d1fae5;
    color: #065f46;
}

.badge {
    font-size: 10px;
    padding: 4px 8px;
}
</style>
@endsection
