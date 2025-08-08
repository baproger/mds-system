@props(['id', 'title', 'message', 'confirmText' => 'Удалить', 'cancelText' => 'Отмена'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">{{ $message }}</p>
                <small class="text-muted">Это действие нельзя отменить</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ $cancelText }}
                </button>
                <button type="button" class="btn btn-danger" id="{{ $id }}Confirm">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div> 