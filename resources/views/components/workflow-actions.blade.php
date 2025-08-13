@props(['contract'])

<div class="workflow-actions">
    @php
        $user = Auth::user();
        $canSubmitToRop = $contract->canPerformAction('submit_to_rop', $user);
        $canSubmitToAccountant = $contract->canPerformAction('submit_to_accountant', $user);
        $canApprove = $contract->canPerformAction('approve', $user);
        $canReject = $contract->canPerformAction('reject', $user);
        $canHold = $contract->canPerformAction('hold', $user);
        $canReturn = $contract->canPerformAction('return', $user);
    @endphp

    @if($canSubmitToRop)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.submit-to-rop' : 'manager.contracts.submit-to-rop', $contract) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm workflow-btn">
                <i class="fas fa-paper-plane"></i>
                Отправить на РОП
            </button>
        </form>
    @endif

    @if($canSubmitToAccountant)
        <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.submit-to-accountant' : 'rop.contracts.submit-to-accountant', $contract) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-info btn-sm workflow-btn">
                <i class="fas fa-paper-plane"></i>
                Отправить бухгалтеру
            </button>
        </form>
    @endif

    @if($canApprove)
        <button type="button" class="btn btn-success btn-sm workflow-btn" onclick="showApproveModal()">
            <i class="fas fa-check-circle"></i>
            Одобрить
        </button>
    @endif

    @if($canReject)
        <button type="button" class="btn btn-danger btn-sm workflow-btn" onclick="showRejectModal()">
            <i class="fas fa-times-circle"></i>
            Отклонить
        </button>
    @endif

    @if($canHold)
        <button type="button" class="btn btn-warning btn-sm workflow-btn" onclick="showHoldModal()">
            <i class="fas fa-pause-circle"></i>
            Приостановить
        </button>
    @endif

    @if($canReturn)
        <button type="button" class="btn btn-info btn-sm workflow-btn" onclick="showReturnModal()">
            <i class="fas fa-undo"></i>
            Вернуть на доработку
        </button>
    @endif

    @if($user->can('view', $contract))
        <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.history' : (Auth::user()->role === 'manager' ? 'manager.contracts.history' : (Auth::user()->role === 'rop' ? 'rop.contracts.history' : 'accountant.contracts.history')), $contract) }}" class="btn btn-secondary btn-sm workflow-btn">
            <i class="fas fa-history"></i>
            История изменений
        </a>
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
