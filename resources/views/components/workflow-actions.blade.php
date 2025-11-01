@props(['contract'])

<div class="workflow-actions-modern">
    @php
        $user = Auth::user();
        $canSubmitToRop = $contract->canPerformAction('submit_to_rop', $user);
        $canApprove = $contract->canPerformAction('approve', $user);
        $canReject = $contract->canPerformAction('reject', $user);
        $canHold = $contract->canPerformAction('hold', $user);
        $canReturn = $contract->canPerformAction('return', $user);
        $canStartProduction = $contract->canPerformAction('start_production', $user);
        $canQualityCheck = $contract->canPerformAction('quality_check', $user);
        $canMarkReady = $contract->canPerformAction('mark_ready', $user);
        $canShip = $contract->canPerformAction('ship', $user);
        $canComplete = $contract->canPerformAction('complete', $user);
    @endphp

    <!-- Заголовок секции -->
    <div class="workflow-header">
        <div class="workflow-title">
            <i class="fas fa-tasks"></i>
            <h3>Действия с договором</h3>
        </div>
        <p class="workflow-subtitle">Выберите действие для изменения статуса договора</p>
    </div>

    <!-- Основные действия -->
    <div class="workflow-grid">
        <!-- Отправка на проверку РОП -->
        @if($canSubmitToRop)
            <div class="action-card submit-rop">
                <div class="action-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="action-content">
                    <h4>Отправить РОП</h4>
                    <p>Отправить договор на проверку руководителю отдела продаж</p>
                    <button type="button" class="action-btn submit-btn" onclick="showSubmitRopModal()">
                        <i class="fas fa-paper-plane"></i>
                        Отправить
                    </button>
                </div>
            </div>
        @endif

        <!-- История изменений -->
        @if($contract->changes()->count() > 0 || $contract->approvals()->count() > 0)
            <div class="action-card history">
                <div class="action-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="action-content">
                    <h4>История изменений</h4>
                    <p>Просмотреть все изменения и одобрения по договору</p>
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.history' : (Auth::user()->role === 'rop' ? 'rop.contracts.history' : 'manager.contracts.history'), $contract) }}" class="action-btn history-btn">
                        <i class="fas fa-history"></i>
                        Просмотреть
                    </a>
                </div>
            </div>
        @endif

        <!-- Одобрение договора -->
        @if($canApprove)
            <div class="action-card approve">
                <div class="action-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="action-content">
                    <h4>Одобрить договор</h4>
                    <p>Одобрить договор для дальнейшей обработки</p>
                    <button type="button" class="action-btn approve-btn" onclick="showApproveModal()">
                        <i class="fas fa-check-circle"></i>
                        Одобрить
                    </button>
                </div>
            </div>
        @endif

        <!-- Отклонение договора -->
        @if($canReject)
            <div class="action-card reject">
                <div class="action-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="action-content">
                    <h4>Отклонить договор</h4>
                    <p>Отклонить договор с указанием причины</p>
                    <button type="button" class="action-btn reject-btn" onclick="showRejectModal()">
                        <i class="fas fa-times-circle"></i>
                        Отклонить
                    </button>
                </div>
            </div>
        @endif

        <!-- Приостановка договора -->
        @if($canHold)
            <div class="action-card hold">
                <div class="action-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="action-content">
                    <h4>Приостановить договор</h4>
                    <p>Временно приостановить выполнение договора</p>
                    <button type="button" class="action-btn hold-btn" onclick="showHoldModal()">
                        <i class="fas fa-pause-circle"></i>
                        Приостановить
                    </button>
                </div>
            </div>
        @endif

        <!-- Возврат на доработку -->
        @if($canReturn)
            <div class="action-card return">
                <div class="action-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="action-content">
                    <h4>Вернуть на доработку</h4>
                    <p>Вернуть договор менеджеру для исправления</p>
                    <button type="button" class="action-btn return-btn" onclick="showReturnModal()">
                        <i class="fas fa-undo"></i>
                        Вернуть
                    </button>
                </div>
            </div>
        @endif

        <!-- Запуск производства -->
        @if($canStartProduction)
            <div class="action-card production">
                <div class="action-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="action-content">
                    <h4>Запустить производство</h4>
                    <p>Перевести договор в статус производства</p>
                    <button type="button" class="action-btn production-btn" onclick="showStartProductionModal()">
                        <i class="fas fa-cogs"></i>
                        Запустить
                    </button>
                </div>
            </div>
        @endif

        <!-- Проверка качества -->
        @if($canQualityCheck)
            <div class="action-card quality">
                <div class="action-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="action-content">
                    <h4>Проверить качество</h4>
                    <p>Отправить на контроль качества</p>
                    <button type="button" class="action-btn quality-btn" onclick="showQualityCheckModal()">
                        <i class="fas fa-search"></i>
                        Проверить
                    </button>
                </div>
            </div>
        @endif

        <!-- Готов к отгрузке -->
        @if($canMarkReady)
            <div class="action-card ready">
                <div class="action-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="action-content">
                    <h4>Готов к отгрузке</h4>
                    <p>Отметить готовность к отгрузке</p>
                    <button type="button" class="action-btn ready-btn" onclick="showMarkReadyModal()">
                        <i class="fas fa-box"></i>
                        Готов
                    </button>
                </div>
            </div>
        @endif

        <!-- Отгрузка -->
        @if($canShip)
            <div class="action-card ship">
                <div class="action-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="action-content">
                    <h4>Отгрузить</h4>
                    <p>Отметить отгрузку договора</p>
                    <button type="button" class="action-btn ship-btn" onclick="showShipModal()">
                        <i class="fas fa-truck"></i>
                        Отгрузить
                    </button>
                </div>
            </div>
        @endif

        <!-- Завершение -->
        @if($canComplete)
            <div class="action-card complete">
                <div class="action-icon">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="action-content">
                    <h4>Завершить</h4>
                    <p>Завершить выполнение договора</p>
                    <button type="button" class="action-btn complete-btn" onclick="showCompleteModal()">
                        <i class="fas fa-flag-checkered"></i>
                        Завершить
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Модальные окна для действий -->
@if($canApprove)
<div class="modal" id="approveModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Одобрить договор</h5>
            <button type="button" class="btn-close" onclick="closeModal('approveModal')">&times;</button>
        </div>
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.approve' : (Auth::user()->role === 'rop' ? 'rop.contracts.approve' : 'manager.contracts.approve'), $contract) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="approve_comment" class="form-label">Комментарий (необязательно)</label>
                    <textarea class="form-control" id="approve_comment" name="comment" rows="3" placeholder="Введите комментарий к одобрению..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('approveModal')">Отмена</button>
                <button type="submit" class="btn btn-success">Одобрить</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($canReject)
<div class="modal" id="rejectModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Отклонить договор</h5>
            <button type="button" class="btn-close" onclick="closeModal('rejectModal')">&times;</button>
        </div>
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.reject' : (Auth::user()->role === 'rop' ? 'rop.contracts.reject' : 'manager.contracts.reject'), $contract) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="reject_comment" class="form-label">Причина отклонения <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="reject_comment" name="comment" rows="3" placeholder="Укажите причину отклонения..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')">Отмена</button>
                <button type="submit" class="btn btn-danger">Отклонить</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($canHold)
<div class="modal" id="holdModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Приостановить договор</h5>
            <button type="button" class="btn-close" onclick="closeModal('holdModal')">&times;</button>
        </div>
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.hold' : (Auth::user()->role === 'rop' ? 'rop.contracts.hold' : 'manager.contracts.hold'), $contract) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="hold_comment" class="form-label">Причина приостановки <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="hold_comment" name="comment" rows="3" placeholder="Укажите причину приостановки..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('holdModal')">Отмена</button>
                <button type="submit" class="btn btn-warning">Приостановить</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($canReturn)
<div class="modal" id="returnModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Вернуть на доработку</h5>
            <button type="button" class="btn-close" onclick="closeModal('returnModal')">&times;</button>
        </div>
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.return' : (Auth::user()->role === 'rop' ? 'rop.contracts.return' : 'manager.contracts.return'), $contract) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="return_comment" class="form-label">Комментарий для доработки <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="return_comment" name="comment" rows="3" placeholder="Укажите, что нужно доработать..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('returnModal')">Отмена</button>
                <button type="submit" class="btn btn-info">Вернуть на доработку</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($canSubmitToRop)
<div class="modal" id="submitRopModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Отправить на проверку РОП</h5>
            <button type="button" class="btn-close" onclick="closeModal('submitRopModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы уверены, что хотите отправить договор на проверку руководителю отдела продаж?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('submitRopModal')">Отмена</button>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.submit-to-rop' : 'manager.contracts.submit-to-rop', $contract) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Да, отправить</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($canStartProduction)
<div class="modal" id="startProductionModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Запустить производство</h5>
            <button type="button" class="btn-close" onclick="closeModal('startProductionModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы уверены, что хотите перевести договор в статус "Производство"?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('startProductionModal')">Отмена</button>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.start-production' : (Auth::user()->role === 'rop' ? 'rop.contracts.start-production' : 'manager.contracts.start-production'), $contract) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Да, перевести</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($canQualityCheck)
<div class="modal" id="qualityCheckModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Проверить качество</h5>
            <button type="button" class="btn-close" onclick="closeModal('qualityCheckModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы уверены, что хотите отправить договор на контроль качества?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('qualityCheckModal')">Отмена</button>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.quality-check' : (Auth::user()->role === 'rop' ? 'rop.contracts.quality-check' : 'manager.contracts.quality-check'), $contract) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Да, отправить</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($canMarkReady)
<div class="modal" id="markReadyModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Готов к отгрузке</h5>
            <button type="button" class="btn-close" onclick="closeModal('markReadyModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы уверены, что хотите отметить договор как "Готов к отгрузке"?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('markReadyModal')">Отмена</button>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.mark-ready' : (Auth::user()->role === 'rop' ? 'rop.contracts.mark-ready' : 'manager.contracts.mark-ready'), $contract) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Да, отметить</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($canShip)
<div class="modal" id="shipModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Отгрузить</h5>
            <button type="button" class="btn-close" onclick="closeModal('shipModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы уверены, что хотите отметить договор как "Отгружен"?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('shipModal')">Отмена</button>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.ship' : (Auth::user()->role === 'rop' ? 'rop.contracts.ship' : 'manager.contracts.ship'), $contract) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Да, отгрузить</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($canComplete)
<div class="modal" id="completeModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title">Завершить</h5>
            <button type="button" class="btn-close" onclick="closeModal('completeModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Вы уверены, что хотите завершить выполнение договора?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('completeModal')">Отмена</button>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.complete' : (Auth::user()->role === 'rop' ? 'rop.contracts.complete' : 'manager.contracts.complete'), $contract) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Да, завершить</button>
            </form>
        </div>
    </div>
</div>
@endif

<style>
/* Современный минималистичный дизайн для workflow actions */
.workflow-actions-modern {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--border-color);
}

/* Заголовок секции */
.workflow-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--border-color);
}

.workflow-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.workflow-title i {
    font-size: 1.5rem;
    color: #3b82f6;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.workflow-title h3 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.5px;
}

.workflow-subtitle {
    margin: 0;
    color: var(--text-secondary);
    font-size: 1rem;
    font-weight: 400;
}

/* Сетка действий */
.workflow-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}

/* Карточки действий */
.action-card {
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    border-color: #3b82f6;
}

.action-card:hover::before {
    transform: scaleX(1);
}

/* Иконки действий */
.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    color: var(--white);
    transition: all 0.3s ease;
}

.action-icon.submit-rop {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.action-icon.approve {
    background: linear-gradient(135deg, #10b981, #059669);
}

.action-icon.reject {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.action-icon.hold {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.action-icon.return {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.action-icon.production {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.action-icon.quality {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

.action-icon.ready {
    background: linear-gradient(135deg, #10b981, #059669);
}

.action-icon.ship {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.action-icon.complete {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.action-icon.history {
    background: linear-gradient(135deg, #6b7280, #4b5563);
}

/* Контент карточки */
.action-content h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
}

.action-content p {
    margin: 0 0 1.5rem 0;
    color: var(--text-secondary);
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Кнопки действий */
.action-btn {
    width: 100%;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.action-btn:hover::before {
    left: 100%;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Цвета кнопок */
.submit-btn {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: var(--white);
}

.approve-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    color: var(--white);
}

.reject-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: var(--white);
}

.hold-btn {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: var(--white);
}

.return-btn {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: var(--white);
}

.production-btn {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: var(--white);
}

.quality-btn {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
    color: var(--white);
}

.ready-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    color: var(--white);
}

.ship-btn {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: var(--white);
}

.complete-btn {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: var(--white);
}

.history-btn {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: var(--white);
}

/* Собственные стили для модальных окон */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    display: none;
    z-index: 9999;
    backdrop-filter: blur(8px);
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Модальный диалог */
.modal-dialog {
    background: var(--bg-card);
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    transform: scale(0.95);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal.show .modal-dialog {
    transform: scale(1);
    opacity: 1;
}

/* Заголовок модального окна */
.modal-header {
    background: var(--bg-card);
    color: var(--text-primary);
    padding: 28px 28px 20px;
    position: relative;
    border-bottom: 1px solid var(--border-color);
}

.modal-title {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    color: var(--text-primary);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Кнопка закрытия */
.btn-close {
    position: absolute;
    top: 24px;
    right: 24px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 50%;
    width: 36px;
    height: 36px;
    color: var(--text-secondary);
    font-size: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 300;
}

.btn-close:hover {
    background: var(--bg-secondary);
    border-color: var(--border-color);
    color: var(--text-primary);
    transform: scale(1.05);
}

/* Тело модального окна */
.modal-body {
    padding: 28px;
    background: var(--bg-card);
}

/* Группа формы */
.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    margin-bottom: 10px;
    font-weight: 500;
    color: var(--text-primary);
    font-size: 14px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.form-control {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.2s ease;
    background: var(--bg-card);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    background: var(--bg-card);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.form-control::placeholder {
    color: var(--text-muted);
}

/* Футер модального окна */
.modal-footer {
    padding: 24px 28px 28px;
    background: var(--bg-secondary);
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 16px;
    justify-content: flex-end;
}

/* Кнопки */
.btn {
    padding: 14px 28px;
    border: none;
    border-radius: 12px;
    font-weight: 500;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 120px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    position: relative;
    overflow: hidden;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

.btn-secondary {
    background: #6b7280;
    color: var(--white);
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-success {
    background: #10b981;
    color: var(--white);
}

.btn-success:hover {
    background: #059669;
}

.btn-primary {
    background: #3b82f6;
    color: var(--white);
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-danger {
    background: #ef4444;
    color: var(--white);
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-warning {
    background: #f59e0b;
    color: var(--white);
}

.btn-warning:hover {
    background: #d97706;
}

.btn-info {
    background: #06b6d4;
    color: var(--white);
}

.btn-info:hover {
    background: #0891b2;
}

/* Анимации */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-30px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.modal.show .modal-dialog {
    animation: modalFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

/* Адаптивность */
@media (max-width: 768px) {
    .workflow-actions-modern {
        padding: 1.5rem;
        border-radius: 16px;
    }
    
    .workflow-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-card {
        padding: 1.25rem;
    }
    
    .workflow-title h3 {
        font-size: 1.5rem;
    }
    
    .workflow-subtitle {
        font-size: 0.875rem;
    }

    .modal-dialog {
        width: 95%;
        margin: 20px;
        border-radius: 16px;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 24px;
    }
    
    .btn {
        padding: 12px 24px;
        font-size: 14px;
        min-width: 100px;
    }
    
    .modal-title {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .workflow-actions-modern {
        padding: 1rem;
    }
    
    .action-card {
        padding: 1rem;
    }
    
    .action-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .action-content h4 {
        font-size: 1rem;
    }
    
    .action-content p {
        font-size: 0.8rem;
    }
}
</style>

<script>
// Собственные функции для модальных окон
function showModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (!modalElement) {
        return;
    }
    
    // Показываем модальное окно
    modalElement.classList.add('show');
    document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
}

function closeModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (!modalElement) {
        return;
    }
    
    // Скрываем модальное окно
    modalElement.classList.remove('show');
    document.body.style.overflow = ''; // Возвращаем прокрутку страницы
}

// Закрытие модального окна при клике на оверлей
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        const modalId = event.target.id;
        closeModal(modalId);
    }
});

// Закрытие модального окна при нажатии Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Функции для показа модальных окон
function showApproveModal() {
    showModal('approveModal');
}

function showRejectModal() {
    showModal('rejectModal');
}

function showHoldModal() {
    showModal('holdModal');
}

function showReturnModal() {
    showModal('returnModal');
}

function showSubmitRopModal() {
    showModal('submitRopModal');
}

function showStartProductionModal() {
    showModal('startProductionModal');
}

function showQualityCheckModal() {
    showModal('qualityCheckModal');
}

function showMarkReadyModal() {
    showModal('markReadyModal');
}

function showShipModal() {
    showModal('shipModal');
}

function showCompleteModal() {
    showModal('completeModal');
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Модальные окна готовы к работе
});
</script>
