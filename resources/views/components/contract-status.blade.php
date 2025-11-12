@props(['contract'])

<div class="contract-status">
    <span class="status-badge status-{{ $contract->status_color }}">
        <i class="fas fa-circle status-icon"></i>
        {{ $contract->status_label }}
    </span>
    
    @if($contract->version > 1)
        <span class="version-badge">
            <i class="fas fa-code-branch"></i>
            v{{ $contract->version }}
        </span>
    @endif
    
    @if($contract->current_reviewer_id)
        <span class="reviewer-badge">
            <i class="fas fa-user-check"></i>
            На рассмотрении у {{ $contract->currentReviewer->name ?? 'неизвестного' }}
        </span>
    @endif
</div>

<style>
.contract-status {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid;
}

.status-icon {
    font-size: 8px;
}

.status-secondary {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border-color: #d1d5db;
}

.status-warning {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
}

.status-info {
    background: #dbeafe;
    color: #1e40af;
    border-color: #93c5fd;
}

.status-success {
    background: #d1fae5;
    color: #065f46;
    border-color: #6ee7b7;
}

.status-danger {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fca5a5;
}

.status-dark {
    background: var(--text-primary);
    color: var(--bg-secondary);
    border-color: var(--text-secondary);
}

.version-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: var(--bg-secondary);
    color: #64748b;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.reviewer-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: #f0f9ff;
    color: #0369a1;
    border: 1px solid #bae6fd;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}
</style>
