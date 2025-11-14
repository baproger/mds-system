@extends('layouts.admin')

@section('title', 'Управление менеджерами')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Менеджеры</h1>
                            <p class="page-subtitle">Управление менеджерами и их эффективностью</p>
                        </div>
                    </div>
                </div>

                <!-- Статистика менеджеров -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Статистика менеджеров</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $managers->total() }}</div>
                                <div class="stat-label">Всего менеджеров</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $managers->where('role', 'rop')->count() }}</div>
                                <div class="stat-label">РОП</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $managers->where('role', 'manager')->count() }}</div>
                                <div class="stat-label">Менеджеров</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $managers->sum('contracts_count') }}</div>
                                <div class="stat-label">Всего договоров</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Поиск и фильтры -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-search"></i>
                        <span>Поиск и фильтры</span>
                    </div>
                    
                    <form method="GET" action="{{ route('admin.managers.index') }}" class="search-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="search" class="form-label">
                                    <i class="fas fa-search"></i>
                                    Поиск
                                </label>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Поиск по имени менеджера..." 
                                       value="{{ request('search') }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="branch" class="form-label">
                                    <i class="fas fa-building"></i>
                                    Филиал
                                </label>
                                <select class="form-control" name="branch">
                                    <option value="">Все филиалы</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag"></i>
                                    Роль
                                </label>
                                <select class="form-control" name="role">
                                    <option value="">Все роли</option>
                                    <option value="rop" {{ request('role') == 'rop' ? 'selected' : '' }}>РОП</option>
                                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Менеджер</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    <i class="fas fa-filter"></i>
                                    Статус
                                </label>
                                <select class="form-control" name="status">
                                    <option value="">Все</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивные</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-filter"></i>
                                    Действия
                                </label>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-save">
                                        <i class="fas fa-search"></i>
                                        Поиск
                                    </button>
                                    <a href="{{ route('admin.managers.index') }}" class="btn btn-cancel">
                                        <i class="fas fa-times"></i>
                                        Сброс
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Список менеджеров -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-list"></i>
                        <span>Список менеджеров</span>
                        <div class="section-actions">
                            <a href="{{ route('admin.managers.create') }}" class="btn btn-save">
                                <i class="fas fa-user-plus"></i>
                                Добавить менеджера
                            </a>
                        </div>
                    </div>
                    
                    @if($managers->count() > 0)
                        <div class="personnel-section">
                            @foreach($managers as $manager)
                                <div class="personnel-item manager-item">
                                    <div class="personnel-icon">
                                        @if($manager->role === 'rop')
                                            <i class="fas fa-crown"></i>
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="personnel-content">
                                        <div class="personnel-title">{{ $manager->name }}</div>
                                        <div class="personnel-list">
                                            <span class="personnel-tag email-tag"><i class="fas fa-envelope tag-icon"></i>{{ $manager->email }}</span>
                                            @if($manager->role === 'rop')
                                                <span class="personnel-tag rop-tag"><i class="fas fa-crown tag-icon"></i>РОП</span>
                                            @else
                                                <span class="personnel-tag manager-tag"><i class="fas fa-user-tag tag-icon"></i>Менеджер</span>
                                            @endif
                                            <span class="personnel-tag branch-tag"><i class="fas fa-building tag-icon"></i>{{ $manager->branch->name }}</span>
                                            <span class="personnel-tag contract-tag">
                                                <i class="fas fa-file-contract tag-icon"></i>
                                                <span style="margin-left:4px;">{{ $manager->contracts_count }}</span>
                                                <span style="margin-left:6px; opacity:0.9;">договоров</span>
                                            </span>
                                            <span class="personnel-tag amount-tag">
                                                <i class="fas fa-money-bill-wave tag-icon"></i>
                                                <span style="margin-left:4px;">{{ number_format($manager->contracts_sum_order_total ?? 0) }} ₸</span>
                                            </span>
                                            <span class="personnel-tag month-tag">
                                                <i class="fas fa-calendar-alt tag-icon"></i>
                                                <span style="margin-left:4px;">{{ $manager->contracts_this_month }}</span>
                                                <span style="margin-left:6px; opacity:0.9;">за месяц</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="personnel-actions">
                                        <a href="{{ route('admin.managers.show', $manager) }}" class="btn btn-sm btn-cancel" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.managers.edit', $manager) }}" class="btn btn-sm btn-save" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($manager->id !== Auth::id())
                                            <button type="button" class="btn btn-sm btn-danger" title="Удалить" 
                                                    onclick="showDeleteModal('{{ $manager->id }}', '{{ $manager->name }}', 'manager')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary" title="Нельзя удалить себя" disabled>
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Пагинация -->
                        @if($managers->hasPages())
                            <div class="pagination-container">
                                {{ $managers->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-tie"></i>
                            <p>Менеджеры не найдены</p>
                        </div>
                    @endif
                </div>
            </div>
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

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.form-group {
    position: relative;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.form-label i {
    color: var(--text-secondary);
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    background: var(--bg-primary);
    box-shadow: 0 0 0 3px rgba(27, 164, 233, 0.15);
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
    background: rgba(147, 51, 234, 0.16);
    color: #0284c7;
}

.manager-item .personnel-icon {
    background: rgba(108, 99, 255, 0.16);
    color: #6c63ff;
}

.dark-mode .personnel-icon {
    background: rgba(147, 51, 234, 0.24);
    color: #d2b4ff;
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

.rop-tag {
    background: rgba(147, 51, 234, 0.16);
    border-color: rgba(147, 51, 234, 0.28);
    color: #0284c7;
}

.dark-mode .rop-tag {
    background: rgba(147, 51, 234, 0.24);
    border-color: rgba(147, 51, 234, 0.35);
    color: #d4b8ff;
}

.manager-tag {
    background: rgba(148, 163, 184, 0.16);
    border-color: rgba(148, 163, 184, 0.26);
    color: var(--text-secondary);
}

.branch-tag {
    background: rgba(37, 99, 235, 0.12);
    border-color: rgba(37, 99, 235, 0.25);
    color: #2563eb;
}

.dark-mode .branch-tag {
    background: rgba(37, 99, 235, 0.18);
    border-color: rgba(37, 99, 235, 0.35);
    color: #9db7ff;
}

.contract-tag,
.amount-tag {
    background: rgba(16, 185, 129, 0.14);
    border-color: rgba(16, 185, 129, 0.25);
    color: #047857;
}

.dark-mode .contract-tag,
.dark-mode .amount-tag {
    background: rgba(16, 185, 129, 0.22);
    border-color: rgba(16, 185, 129, 0.35);
    color: #7ce0b9;
}

.month-tag {
    background: rgba(148, 163, 184, 0.14);
    border-color: rgba(148, 163, 184, 0.24);
    color: var(--text-secondary);
}

.email-tag {
    background: rgba(27, 164, 233, 0.14);
    border-color: rgba(27, 164, 233, 0.25);
    color: #1381bf;
}

.dark-mode .email-tag {
    background: rgba(27, 164, 233, 0.18);
    border-color: rgba(27, 164, 233, 0.35);
    color: #8bd1ff;
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

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #1ba4e9;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
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

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 24px;
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
    
    .form-grid {
        grid-template-columns: 1fr;
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

/* Стили для кнопки удаления */
.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #1ba4e9;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

/* Стили для модального окна */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease-out;
}

.modal-content {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 32px;
    max-width: 480px;
    width: 90%;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    animation: slideIn 0.3s ease-out;
    position: relative;
}

.modal-header {
    text-align: center;
    margin-bottom: 24px;
}

.modal-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: #1ba4e9;
    font-size: 24px;
}

.modal-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.modal-subtitle {
    font-size: 16px;
    color: var(--text-secondary);
    line-height: 1.5;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 32px;
}

.modal-btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 120px;
}

.modal-btn-cancel {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.modal-btn-cancel:hover {
    background: var(--border-color);
    transform: translateY(-1px);
}

.modal-btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #1ba4e9;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
}

.modal-btn-delete:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

@keyframes slideIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}
</style>

<!-- Модальное окно удаления -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="modal-title">Подтверждение удаления</h3>
            <p class="modal-subtitle">
                Вы действительно хотите удалить <strong id="deleteItemName"></strong>? 
                Это действие нельзя отменить.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">
                <i class="fas fa-times"></i>
                Отмена
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-delete">
                    <i class="fas fa-trash"></i>
                    Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteModal(id, name, type) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = type === 'user' 
        ? `/admin/users/${id}` 
        : `/admin/managers/${id}`;
    document.getElementById('deleteModal').style.display = 'flex';
}

function hideDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Закрытие модального окна при клике вне его
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});
</script>
@endsection 