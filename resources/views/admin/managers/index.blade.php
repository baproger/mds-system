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
    color: #fff;
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
    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
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
    color: #6b7280;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
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
    color: #374151;
    margin-bottom: 8px;
}

.form-label i {
    color: #6b7280;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #fafafa;
}

.form-control:focus {
    outline: none;
    border-color: #1ba4e9;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

.manager-item .personnel-icon {
    background: #f3e8ff;
    color: #7c3aed;
}

.personnel-content {
    flex: 1;
    min-width: 0;
}

.personnel-title {
    font-weight: 600;
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 8px;
}

.personnel-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.personnel-tag {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    transition: all 0.2s ease;
    border: 1px solid;
}

.rop-tag {
    background: #eef2ff;
    color: #7c3aed;
    border: 1px solid #c7d2fe;
}

.manager-tag {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
}

.branch-tag {
    background: #f0f9ff;
    color: #0369a1;
    border-color: #bae6fd;
}

.contract-tag {
    background: #f0fdf4;
    color: #166534;
    border-color: #bbf7d0;
}

.amount-tag {
    background: #f0fdf4;
    color: #166534;
    border-color: #bbf7d0;
}

.month-tag {
    background: #f3f4f6;
    color: #6b7280;
    border-color: #d1d5db;
}

.email-tag {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #bfdbfe;
}

.tag-icon { margin-right: 6px; opacity: 0.85; }

.personnel-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.btn {
    .btn i { color: #fff !important; }    display: inline-flex;
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
    .btn i { color: #fff !important; }    padding: 8px 12px;
    font-size: 12px;
}

.btn-cancel {
    .btn i { color: #fff !important; }    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-cancel:hover {
    .btn i { color: #fff !important; }    background: #e5e7eb;
    transform: translateY(-1px);
}

.btn-save {
    .btn i { color: #fff !important; }    background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

.btn-save:hover {
    .btn i { color: #fff !important; }    transform: translateY(-1px);
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
    .btn i { color: #fff !important; }    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
}

.btn-danger:hover {
    .btn i { color: #fff !important; }    transform: translateY(-1px);
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
    background: white;
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
    color: white;
    font-size: 24px;
}

.modal-title {
    font-size: 24px;
    font-weight: 700;
    color: #6b7280;
    margin-bottom: 8px;
}

.modal-subtitle {
    font-size: 16px;
    color: #6b7280;
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
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.modal-btn-cancel:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

.modal-btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
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