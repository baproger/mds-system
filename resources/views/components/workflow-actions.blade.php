@props(['contract'])

<div class="workflow-actions">
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

    <!-- Отправка на проверку РОП -->
    @if($canSubmitToRop)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.submit-to-rop' : 'manager.contracts.submit-to-rop', $contract) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm workflow-btn" title="Отправить на проверку РОП">
                <i class="fas fa-paper-plane"></i>
                Отправить РОП
            </button>
        </form>
    @endif

    <!-- История изменений -->
    @if($contract->changes()->count() > 0 || $contract->approvals()->count() > 0)
        <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.history' : (Auth::user()->role === 'rop' ? 'rop.contracts.history' : 'manager.contracts.history'), $contract) }}" class="btn btn-secondary btn-sm workflow-btn">
            <i class="fas fa-history"></i>
            История
        </a>
    @endif

    <!-- Одобрение договора -->
    @if($canApprove)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.approve' : (Auth::user()->role === 'rop' ? 'rop.contracts.approve' : 'manager.contracts.approve'), $contract) }}" method="POST">
            @csrf
            <div class="input-group input-group-sm">
                <input type="text" name="comment" class="form-control" placeholder="Комментарий к одобрению" required>
                <button type="submit" class="btn btn-success btn-sm workflow-btn" title="Одобрить договор">
                    <i class="fas fa-check-circle"></i>
                    Одобрить
                </button>
            </div>
        </form>
    @endif

    <!-- Отклонение договора -->
    @if($canReject)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.reject' : (Auth::user()->role === 'rop' ? 'rop.contracts.reject' : 'manager.contracts.reject'), $contract) }}" method="POST">
            @csrf
            <div class="input-group input-group-sm">
                <input type="text" name="comment" class="form-control" placeholder="Причина отклонения" required>
                <button type="submit" class="btn btn-danger btn-sm workflow-btn" title="Отклонить договор">
                    <i class="fas fa-times-circle"></i>
                    Отклонить
                </button>
            </div>
        </form>
    @endif

    <!-- Приостановка договора -->
    @if($canHold)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.hold' : (Auth::user()->role === 'rop' ? 'rop.contracts.hold' : 'manager.contracts.hold'), $contract) }}" method="POST">
            @csrf
            <div class="input-group input-group-sm">
                <input type="text" name="comment" class="form-control" placeholder="Причина приостановки" required>
                <button type="submit" class="btn btn-warning btn-sm workflow-btn" title="Приостановить договор">
                    <i class="fas fa-pause-circle"></i>
                    Приостановить
                </button>
            </div>
        </form>
    @endif

    <!-- Возврат на доработку -->
    @if($canReturn)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.return' : (Auth::user()->role === 'rop' ? 'rop.contracts.return' : 'manager.contracts.return'), $contract) }}" method="POST">
            @csrf
            <div class="input-group input-group-sm">
                <input type="text" name="comment" class="form-control" placeholder="Что нужно исправить" required>
                <button type="submit" class="btn btn-info btn-sm workflow-btn" title="Вернуть на доработку">
                    <i class="fas fa-undo"></i>
                    Вернуть
                </button>
            </div>
        </form>
    @endif

    <!-- Запуск производства -->
    @if($canStartProduction)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.start-production' : (Auth::user()->role === 'rop' ? 'rop.contracts.start-production' : 'manager.contracts.start-production'), $contract) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm workflow-btn" title="Запустить производство">
                <i class="fas fa-cogs"></i>
                Запустить производство
            </button>
        </form>
    @endif

    <!-- Проверка качества -->
    @if($canQualityCheck)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.quality-check' : (Auth::user()->role === 'rop' ? 'rop.contracts.quality-check' : 'manager.contracts.quality-check'), $contract) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-info btn-sm workflow-btn" title="Проверить качество">
                <i class="fas fa-search"></i>
                Проверить качество
            </button>
        </form>
    @endif

    <!-- Готов к отгрузке -->
    @if($canMarkReady)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.mark-ready' : (Auth::user()->role === 'rop' ? 'rop.contracts.mark-ready' : 'manager.contracts.mark-ready'), $contract) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success btn-sm workflow-btn" title="Отметить готовность">
                <i class="fas fa-box"></i>
                Готов к отгрузке
            </button>
        </form>
    @endif

    <!-- Отгрузка -->
    @if($canShip)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.ship' : (Auth::user()->role === 'rop' ? 'rop.contracts.ship' : 'manager.contracts.ship'), $contract) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm workflow-btn" title="Отгрузить">
                <i class="fas fa-truck"></i>
                Отгрузить
            </button>
        </form>
    @endif

    <!-- Завершение -->
    @if($canComplete)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.complete' : (Auth::user()->role === 'rop' ? 'rop.contracts.complete' : 'manager.contracts.complete'), $contract) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success btn-sm workflow-btn" title="Завершить">
                <i class="fas fa-flag-checkered"></i>
                Завершить
            </button>
        </form>
    @endif
</div>

<!-- Модальные окна для действий -->
@if($canApprove)
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Одобрить договор</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.approve' : 'accountant.contracts.approve', $contract) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approve_comment" class="form-label">Комментарий (необязательно)</label>
                        <textarea class="form-control" id="approve_comment" name="comment" rows="3" placeholder="Введите комментарий к одобрению..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-success">Одобрить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($canReject)
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Отклонить договор</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.reject' : 'accountant.contracts.reject', $contract) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reject_comment" class="form-label">Причина отклонения *</label>
                        <textarea class="form-control" id="reject_comment" name="comment" rows="3" placeholder="Укажите причину отклонения..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отклонить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($canHold)
<div class="modal fade" id="holdModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Приостановить договор</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.hold' : 'accountant.contracts.hold', $contract) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="hold_comment" class="form-label">Причина приостановки *</label>
                        <textarea class="form-control" id="hold_comment" name="comment" rows="3" placeholder="Укажите причину приостановки..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-warning">Приостановить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($canReturn)
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Вернуть на доработку</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.return' : 'accountant.contracts.return', $contract) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="return_comment" class="form-label">Комментарий для доработки *</label>
                        <textarea class="form-control" id="return_comment" name="comment" rows="3" placeholder="Укажите, что нужно доработать..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-info">Вернуть на доработку</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.workflow-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.workflow-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.workflow-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.workflow-btn i {
    font-size: 11px;
}
</style>

<script>
function showApproveModal() {
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function showRejectModal() {
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function showHoldModal() {
    new bootstrap.Modal(document.getElementById('holdModal')).show();
}

function showReturnModal() {
    new bootstrap.Modal(document.getElementById('returnModal')).show();
}
</script>
