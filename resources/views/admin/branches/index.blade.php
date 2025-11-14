@extends('layouts.admin')

@section('title', 'Управление филиалами')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Филиалы</h1>
                            <p class="page-subtitle">Управление филиалами и их настройками</p>
                        </div>
                    </div>
                </div>

                <!-- Статистика филиалов -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Статистика филиалов</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $branches->count() }}</div>
                                <div class="stat-label">Всего филиалов</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $branches->sum('users_count') }}</div>
                                <div class="stat-label">Всего пользователей</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $branches->sum('sales_staff_count') }}</div>
                                <div class="stat-label">Всего продавцов</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $branches->sum('contracts_count') }}</div>
                                <div class="stat-label">Всего договоров</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Список филиалов -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-list"></i>
                        <span>Список филиалов</span>
                        <div class="section-actions">
                            <a href="{{ route('admin.branches.create') }}" class="btn btn-save">
                                <i class="fas fa-plus"></i>
                                Добавить филиал
                            </a>
                        </div>
                    </div>
                    
                    @if($branches->count() > 0)
                        <div class="personnel-section">
                            @foreach($branches as $branch)
                                <div class="personnel-item branch-item">
                                    <div class="personnel-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="personnel-content">
                                        <div class="personnel-title">{{ $branch->name }}</div>
                                        <div class="personnel-list">
                                            <span class="personnel-tag code-tag"><i class="fas fa-hashtag tag-icon"></i>{{ $branch->code }}</span>
                                            <span class="personnel-tag users-tag"><i class="fas fa-users tag-icon"></i>{{ $branch->users_count }} пользователей</span>
                                            <span class="personnel-tag staff-tag"><i class="fas fa-user-tie tag-icon"></i>{{ $branch->sales_staff_count ?? 0 }} менеджеров</span>
                                            <span class="personnel-tag contract-tag"><i class="fas fa-file-contract tag-icon"></i>{{ $branch->contracts_count }} договоров</span>
                                            @php
                                                $ranges = [
                                                    'SHY-PP' => '20000-29999',
                                                    'SHY-RZ' => '30000-39999',
                                                    'AKT' => '40000-49999',
                                                    'ALA-TST' => '50000-57999',
                                                    'ALA-SC' => '58000-59999',
                                                    'TRZ' => '100000-119999',
                                                    'ATR' => '120000-139999',
                                                    'TAS' => '60000-69999',
                                                ];
                                                $range = $ranges[$branch->code] ?? 'неизвестен';
                                            @endphp
                                            <span class="personnel-tag range-tag"><i class="fas fa-list-ol tag-icon"></i>{{ $range }}</span>
                                        </div>
                                    </div>
                                    <div class="personnel-actions">
                                        <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-sm btn-save" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($branch->contracts_count == 0 && $branch->users_count == 0)
                                            <button type="button" class="btn btn-sm btn-cancel" 
                                                    onclick="showDeleteModal({{ $branch->id }}, '{{ $branch->name }}')" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-cancel" 
                                                    title="Нельзя удалить - есть данные" disabled>
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <p>Филиалы не найдены</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Кастомное модальное окно удаления -->
<div class="custom-modal" id="deleteModal">
    <div class="custom-modal-content">
        <div class="custom-modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="custom-modal-title">Подтверждение удаления</div>
        <div class="custom-modal-message" id="deleteMessage">
            Вы уверены, что хотите удалить этот филиал?
        </div>
        <div class="custom-modal-warning">
            Это действие нельзя отменить
        </div>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn custom-modal-btn-secondary" onclick="hideDeleteModal()">
                Отмена
            </button>
            <button type="button" class="custom-modal-btn custom-modal-btn-danger" onclick="confirmDelete()">
                Удалить
            </button>
        </div>
    </div>
</div>

<style>
.edit-branch-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.page-header {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-color);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: #e0f2fe;
    color: #1ba4e9;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-secondary);
    margin: 0;
}

.page-subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 4px 0 0 0;
}

.form-section {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.form-section:hover {
    box-shadow: var(--shadow-md);
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    font-size: 16px;
    color: var(--text-primary);
}

.section-header i {
    color: var(--accent-primary);
    font-size: 18px;
}

.section-actions {
    display: flex;
    gap: 12px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    background: rgba(27, 164, 233, 0.12);
    color: #1ba4e9;
}

.dark-mode .stat-icon {
    background: rgba(27, 164, 233, 0.2);
    color: #7cc7f5;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.personnel-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.personnel-item {
    display: flex;
    align-items: flex-start;
    padding: 18px 20px;
    background: var(--bg-card);
    border-radius: 14px;
    border: 1px solid var(--border-color);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.personnel-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: rgba(27, 164, 233, 0.35);
}

.personnel-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    flex-shrink: 0;
    background: rgba(37, 99, 235, 0.14);
    color: #2563eb;
}

.dark-mode .personnel-icon {
    background: rgba(37, 99, 235, 0.22);
    color: #93b3ff;
}

.personnel-content {
    flex: 1;
    min-width: 0;
}

.personnel-title {
    font-weight: 600;
    font-size: 16px;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.personnel-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.personnel-tag {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid transparent;
    background: var(--bg-secondary);
    color: var(--text-secondary);
}

.personnel-tag:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.code-tag {
    background: rgba(245, 158, 11, 0.16);
    border-color: rgba(245, 158, 11, 0.28);
    color: #b7791f;
}

.dark-mode .code-tag {
    background: rgba(245, 158, 11, 0.22);
    border-color: rgba(245, 158, 11, 0.35);
    color: #f7c87a;
}

.users-tag {
    background: rgba(37, 99, 235, 0.12);
    border-color: rgba(37, 99, 235, 0.25);
    color: #2563eb;
}

.dark-mode .users-tag {
    background: rgba(37, 99, 235, 0.18);
    border-color: rgba(37, 99, 235, 0.35);
    color: #9db7ff;
}

.staff-tag {
    background: rgba(148, 163, 184, 0.16);
    border-color: rgba(148, 163, 184, 0.26);
    color: var(--text-secondary);
}

.contract-tag {
    background: rgba(16, 185, 129, 0.14);
    border-color: rgba(16, 185, 129, 0.25);
    color: #047857;
}

.dark-mode .contract-tag {
    background: rgba(16, 185, 129, 0.22);
    border-color: rgba(16, 185, 129, 0.35);
    color: #79ddb6;
}

.range-tag {
    background: rgba(124, 58, 237, 0.16);
    border-color: rgba(124, 58, 237, 0.28);
    color: #0284c7;
}

.dark-mode .range-tag {
    background: rgba(124, 58, 237, 0.22);
    border-color: rgba(124, 58, 237, 0.35);
    color: #c9adff;
}

.tag-icon { margin-right: 6px; opacity: 0.85; }

.personnel-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 12px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: var(--border-color);
    transform: translateY(-1px);
}

.btn-primary {
    background: #e0f2fe;
    color: #1ba4e9;
    color: #1ba4e9;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(27, 164, 233, 0.3);
}

.btn-cancel {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.btn-cancel:hover {
    background: var(--border-color);
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 16px;
    margin: 0;
}

/* Адаптивность */
@media (max-width: 768px) {
    .edit-branch-container {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .personnel-item {
        flex-direction: column;
        gap: 12px;
    }
    
    .personnel-actions {
        align-self: flex-end;
    }
}
</style>

<script>
let currentDeleteId = null;

function showDeleteModal(branchId, branchName) {
    currentDeleteId = branchId;
    document.getElementById('deleteMessage').textContent = `Вы уверены, что хотите удалить филиал "${branchName}"?`;
    document.getElementById('deleteModal').classList.add('show');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    currentDeleteId = null;
}

function confirmDelete() {
    if (currentDeleteId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/branches/${currentDeleteId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
    hideDeleteModal();
}

// Закрытие модального окна при клике вне его
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// Закрытие модального окна при нажатии Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
    }
});
</script>
@endsection 