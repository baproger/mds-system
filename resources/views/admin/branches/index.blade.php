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
                                            <span class="personnel-tag code-tag">{{ $branch->code }}</span>
                                            <span class="personnel-tag users-tag">{{ $branch->users_count }} пользователей</span>
                                            <span class="personnel-tag staff-tag">{{ $branch->sales_staff_count ?? 0 }} менеджеров</span>
                                            <span class="personnel-tag contract-tag">{{ $branch->contracts_count }} договоров</span>
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
                                            <span class="personnel-tag range-tag">{{ $range }}</span>
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    color: #111827;
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
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
    font-weight: 600;
    font-size: 16px;
    color: #374151;
}

.section-header i {
    color: #667eea;
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
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.personnel-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.personnel-item {
    display: flex;
    align-items: flex-start;
    padding: 16px;
    background: #fafafa;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.personnel-item:hover {
    background: #f8f9fa;
    border-color: #e9ecef;
    transform: translateY(-1px);
}

.personnel-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.branch-item .personnel-icon {
    background: #eff6ff;
    color: #2563eb;
}

.personnel-content {
    flex: 1;
    min-width: 0;
}

.personnel-title {
    font-weight: 600;
    font-size: 16px;
    color: #111827;
    margin-bottom: 8px;
}

.personnel-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.personnel-tag {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    transition: all 0.2s ease;
}

.code-tag {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.users-tag {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.staff-tag {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
}

.contract-tag {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.range-tag {
    background: #f3e8ff;
    color: #7c3aed;
    border: 1px solid #c7d2fe;
}

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
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 12px;
}

.btn-cancel {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: #6b7280;
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

/* Модальное окно */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.custom-modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-modal-content {
    background: white;
    border-radius: 12px;
    padding: 32px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.custom-modal-icon {
    width: 64px;
    height: 64px;
    background: #fef3c7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: #d97706;
    font-size: 24px;
}

.custom-modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.custom-modal-message {
    color: #6b7280;
    margin-bottom: 16px;
}

.custom-modal-warning {
    background: #fef2f2;
    color: #dc2626;
    padding: 12px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 24px;
}

.custom-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.custom-modal-btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.custom-modal-btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.custom-modal-btn-secondary:hover {
    background: #e5e7eb;
}

.custom-modal-btn-danger {
    background: #dc2626;
    color: white;
}

.custom-modal-btn-danger:hover {
    background: #b91c1c;
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

.personnel-item {
    animation: fadeIn 0.3s ease-out;
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