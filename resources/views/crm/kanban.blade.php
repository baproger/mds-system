@extends('layouts.admin')

@section('title', 'Канбан-доска')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-trello"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Канбан-доска</h1>
                            <p class="page-subtitle">Визуальное управление договорами</p>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-columns"></i>
                        <span>Доска договоров</span>
                    </div>
                    
                    <div class="kanban-board">
                        @foreach($statuses as $status)
                        <div class="kanban-column" data-status="{{ $status }}">
                            <div class="column-header">
                                <div class="column-title">
                                    <div class="status-icon" style="background-color: {{ \App\Models\Contract::getStatusColor($status) }}">
                                        <i class="{{ \App\Models\Contract::getStatusIcon($status) }}"></i>
                                    </div>
                                    <div class="status-info">
                                        <div class="status-name">{{ \App\Models\Contract::getStatusLabel($status) }}</div>
                                        <div class="status-count">{{ isset($contractsByStatus[$status]) ? $contractsByStatus[$status]->count() : 0 }} договоров</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column-content" id="column-{{ $status }}">
                                @if(isset($contractsByStatus[$status]))
                                    @foreach($contractsByStatus[$status] as $contract)
                                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                                        <div class="contract-header">
                                            <div class="contract-number">#{{ $contract->contract_number }}</div>
                                            <div class="contract-amount">{{ number_format($contract->order_total, 0, ',', ' ') }} ₸</div>
                                        </div>
                                        
                                        <div class="contract-body">
                                            <div class="contract-client">{{ $contract->client }}</div>
                                            <div class="contract-date">{{ $contract->created_at->format('d.m.Y') }}</div>
                                        </div>
                                        
                                        <div class="contract-footer">
                                            <div class="contract-manager">
                                                <i class="fas fa-user tag-icon"></i>
                                                {{ $contract->manager ?? 'Не назначен' }}
                                            </div>
                                            <div class="contract-actions">
                                                <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" 
                                                   class="btn-action" title="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="contract-progress">
                                            <div class="progress">
                                                <div class="progress-bar" style="width: {{ $contract->funnel_progress }}%"></div>
                                            </div>
                                            <small class="progress-text">{{ $contract->funnel_progress }}% выполнено</small>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
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
    max-width: 1400px;
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

.kanban-board {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.kanban-column {
    background: #fafafa;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #f3f4f6;
    min-height: 600px;
    display: flex;
    flex-direction: column;
    transition: all 0.2s ease;
}

.kanban-column.drag-over {
    background: #f0f9ff;
    border: 2px dashed #1ba4e9;
    transform: translateY(-2px);
}

.column-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.column-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #618ee6;
    font-size: 16px;
    flex-shrink: 0;
}

.status-info {
    flex: 1;
}

.status-name {
    font-weight: 600;
    color: #374151;
    font-size: 16px;
    margin-bottom: 4px;
}

.status-count {
    font-size: 14px;
    color: #6b7280;
}

.column-content {
    flex: 1;
    min-height: 400px;
    padding: 10px 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contract-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    cursor: grab;
    transition: all 0.3s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.contract-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
    border-color: #1ba4e9;
}

.contract-card:active {
    cursor: grabbing;
}

.contract-card.dragging {
    opacity: 0.6;
    transform: rotate(5deg) scale(0.95);
}

.contract-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.contract-number {
    font-weight: 600;
    color: #374151;
    font-size: 15px;
}

.contract-amount {
    font-weight: 600;
    color: #10b981;
    font-size: 14px;
}

.contract-body {
    margin-bottom: 12px;
}

.contract-client {
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
    font-size: 14px;
}

.contract-date {
    font-size: 13px;
    color: #6b7280;
}

.contract-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.contract-manager {
    font-size: 13px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 6px;
}

.contract-actions {
    display: flex;
    gap: 8px;
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

.contract-progress {
    margin-top: 12px;
}

.progress {
    height: 6px;
    background: #f3f4f6;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 6px;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #1ba4e9, #ac76e3);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 12px;
    color: #6b7280;
    text-align: center;
    display: block;
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

.contract-card {
    animation: fadeIn 0.3s ease-out;
}

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .kanban-board {
        grid-template-columns: 1fr;
    }
    
    .kanban-column {
        min-height: unset;
    }
}

@media (max-width: 1024px) {
    .kanban-board {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация Sortable для каждой колонки
    const columns = document.querySelectorAll('.kanban-column');
    columns.forEach(column => {
        new Sortable(column.querySelector('.column-content'), {
            group: 'contracts',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                const contractId = evt.item.dataset.contractId;
                const newStatus = evt.to.dataset.status;
                
                console.log('Drag & Drop Debug:', {
                    contractId,
                    newStatus,
                    toElement: evt.to,
                    toDataset: evt.to.dataset,
                    fromStatus: evt.from.dataset.status,
                    toStatus: evt.to.dataset.status
                });
                
                // Валидация статуса перед отправкой
                if (!newStatus || newStatus.trim() === '') {
                    console.error('Ошибка: новый статус пустой или неопределен', { newStatus, to: evt.to });
                    showNotification('Ошибка: не удалось определить новый статус', 'error');
                    return;
                }
                
                if (evt.from !== evt.to) {
                    console.log('Обновление статуса:', { contractId, newStatus, from: evt.from.dataset.status, to: evt.to.dataset.status });
                    updateContractStatus(contractId, newStatus);
                }
            }
        });
    });
    
    // Добавляем визуальную обратную связь при перетаскивании
    columns.forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.closest('.kanban-column').classList.add('drag-over');
        });
        
        column.addEventListener('dragleave', function(e) {
            this.closest('.kanban-column').classList.remove('drag-over');
        });
        
        column.addEventListener('drop', function(e) {
            this.closest('.kanban-column').classList.remove('drag-over');
        });
    });
});

function updateContractStatus(contractId, newStatus) {
    // Дополнительная валидация на фронтенде
    if (!contractId || !newStatus || newStatus.trim() === '') {
        console.error('Ошибка валидации:', { contractId, newStatus });
        showNotification('Ошибка: некорректные данные для обновления', 'error');
        return;
    }
    
    const url = `{{ route(Auth::user()->role . '.crm.update-status', ['contract' => ':contractId']) }}`.replace(':contractId', contractId);
    
    console.log('Отправка запроса на обновление статуса:', { contractId, newStatus, url });
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => {
        console.log('Ответ сервера:', response.status, response.statusText);
        return response.json();
    })
    .then(data => {
        console.log('Данные ответа:', data);
        if (data.success) {
            // Обновляем счетчики
            updateColumnCounters();
            showNotification('Статус договора обновлен', 'success');
            
            // НЕ перезагружаем страницу, а обновляем данные через AJAX
            refreshKanbanData();
        } else {
            showNotification('Ошибка при обновлении статуса: ' + (data.error || 'Неизвестная ошибка'), 'error');
            
            // Возвращаем карточку на исходное место при ошибке
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Ошибка fetch:', error);
        showNotification('Ошибка при обновлении статуса', 'error');
        
        // Возвращаем карточку на исходное место при ошибке
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    });
}

// Функция для обновления данных канбан-доски без перезагрузки
function refreshKanbanData() {
    const url = `{{ route(Auth::user()->role . '.crm.kanban-data') }}`;
    const branchId = document.getElementById('branchFilter')?.value || '';
    const userId = document.getElementById('managerFilter')?.value || '';
    
    fetch(url + `?branch_id=${branchId}&user_id=${userId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        // Обновляем данные на доске
        updateKanbanBoard(data);
    })
    .catch(error => {
        console.error('Ошибка при обновлении данных:', error);
        // При ошибке обновления данных перезагружаем страницу
        setTimeout(() => {
            window.location.reload();
        }, 3000);
    });
}

// Функция для обновления канбан-доски новыми данными
function updateKanbanBoard(contractsByStatus) {
    // Обновляем каждую колонку
    Object.keys(contractsByStatus).forEach(status => {
        const column = document.querySelector(`[data-status="${status}"]`);
        if (column) {
            const columnContent = column.querySelector('.column-content');
            columnContent.innerHTML = '';
            
            // Добавляем договоры в колонку
            contractsByStatus[status].forEach(contract => {
                const contractCard = createContractCard(contract);
                columnContent.appendChild(contractCard);
            });
        }
    });
    
    // Обновляем счетчики
    updateColumnCounters();
}

// Функция для создания карточки договора
function createContractCard(contract) {
    const card = document.createElement('div');
    card.className = 'contract-card';
    card.dataset.contractId = contract.id;
    card.draggable = true;
    
    card.innerHTML = `
        <div class="contract-header">
            <div class="contract-number">#${contract.contract_number}</div>
            <div class="contract-amount">${new Intl.NumberFormat('ru-RU').format(contract.order_total)} ₸</div>
        </div>
        
        <div class="contract-body">
            <div class="contract-client">${contract.client}</div>
            <div class="contract-date">${new Date(contract.created_at).toLocaleDateString('ru-RU')}</div>
        </div>
        
        <div class="contract-footer">
            <div class="contract-manager">
                <i class="fas fa-user tag-icon"></i>
                ${contract.manager || 'Не назначен'}
            </div>
            <div class="contract-progress">
                <div class="progress">
                    <div class="progress-bar" style="width: ${contract.funnel_progress}%"></div>
                </div>
                <span class="progress-text">${contract.funnel_progress}% выполнено</span>
            </div>
        </div>
        
        <div class="contract-actions">
            <a href="${getContractShowUrl(contract.id)}" class="btn-action" title="Просмотр">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    `;
    
    return card;
}

// Функция для получения URL просмотра договора
function getContractShowUrl(contractId) {
    const userRole = '{{ Auth::user()->role }}';
    return `/${userRole}/contracts/${contractId}`;
}

function updateColumnCounters() {
    const columns = document.querySelectorAll('.kanban-column');
    
    columns.forEach(column => {
        const status = column.dataset.status;
        const count = column.querySelectorAll('.contract-card').length;
        const counter = column.querySelector('.status-count');
        
        if (counter) {
            counter.textContent = `${count} договоров`;
        }
    });
}

function showNotification(message, type) {
    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Добавляем стили
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    // Цвета для разных типов уведомлений
    if (type === 'success') {
        notification.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    } else if (type === 'error') {
        notification.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    } else if (type === 'warning') {
        notification.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
    } else {
        notification.style.background = 'linear-gradient(135deg, #3b82f6, #2563eb)';
    }
    
    // Добавляем в DOM
    document.body.appendChild(notification);
    
    // Удаляем через 5 секунд
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// CSS анимации для уведомлений
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endsection