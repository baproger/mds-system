@extends('layouts.admin')

@section('title', 'Менеджеры')

@section('content')
@php
    use Illuminate\Pagination\LengthAwarePaginator;

    $mgrCollection = $managers instanceof LengthAwarePaginator ? $managers->getCollection() : collect($managers);
    $totalManagers   = $managers instanceof LengthAwarePaginator ? $managers->total() : $mgrCollection->count();
    $ropCount        = $mgrCollection->where('role', 'rop')->count();
    $managerCount    = $mgrCollection->where('role', 'manager')->count();
    $totalContracts  = $mgrCollection->sum('contracts_count');
@endphp

<div class="edit-branch-container">
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Менеджеры</h1>
                <p class="page-subtitle">Управление менеджерами вашего филиала</p>
            </div>
        </div>
    </div>

    <!-- Статистика -->
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <span>Статистика</span>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalManagers }}</div>
                    <div class="stat-label">Всего менеджеров</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-crown"></i></div>
                <div class="stat-content">
                    <div class="stat-number">{{ $ropCount }}</div>
                    <div class="stat-label">РОП</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user"></i></div>
                <div class="stat-content">
                    <div class="stat-number">{{ $managerCount }}</div>
                    <div class="stat-label">Менеджеров</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-contract"></i></div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalContracts }}</div>
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

        <form method="GET" action="{{ route('rop.managers.index') }}" class="search-form">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-search"></i> Поиск
                    </label>
                    <input type="text" class="form-control" name="search"
                           placeholder="Поиск по имени менеджера..."
                           value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-users"></i> Роль
                    </label>
                    <select class="form-control" name="role">
                        <option value="">Все роли</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Менеджер</option>
                        <option value="rop" {{ request('role') == 'rop' ? 'selected' : '' }}>РОП</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-toggle-on"></i> Статус
                    </label>
                    <select class="form-control" name="status">
                        <option value="">Все статусы</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивные</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-search"></i> Найти
                </button>
                <a href="{{ route('rop.managers.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Сбросить
                </a>
            </div>
        </form>
    </div>

    <!-- Список менеджеров -->
    <div class="form-section">
        <div class="section-header">
            <i class="fas fa-list"></i>
            <span>Список менеджеров</span>
            <div class="section-actions">
                <a href="{{ route('rop.managers.create') }}" class="btn btn-save">
                    <i class="fas fa-user-plus"></i> Добавить менеджера
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
                                @if($manager->branch)
                                    <span class="personnel-tag branch-tag"><i class="fas fa-building tag-icon"></i>{{ $manager->branch->name }}</span>
                                @endif
                                <span class="personnel-tag contract-tag">
                                    <i class="fas fa-file-contract tag-icon"></i>
                                    <span style="margin-left:4px;">{{ $manager->contracts_count }}</span>
                                    <span style="margin-left:6px; opacity:0.9;">договоров</span>
                                    <i class="fas fa-calendar-alt" style="margin-left: 4px; opacity: 0.7;"></i>
                                </span>
                                <span class="personnel-tag amount-tag">
                                    <i class="fas fa-money-bill-wave tag-icon"></i>
                                    <span style="margin-left:4px;">{{ number_format($manager->contracts_sum_order_total ?? ($manager->contracts->sum('order_total') ?? 0), 0, ',', ' ') }} ₸</span>
                                </span>
                                <span class="personnel-tag month-tag">
                                    <i class="fas fa-calendar-alt tag-icon"></i>
                                    <span style="margin-left:4px;">
                                        {{ method_exists($manager, 'contracts') ? $manager->contracts->where('created_at', '>=', now()->startOfMonth())->count() : ($manager->contracts_this_month ?? 0) }}
                                    </span>
                                    <span style="margin-left:6px; opacity:0.9;">за месяц</span>
                                </span>
                            </div>
                        </div>
                        <div class="personnel-actions">
                            <a href="{{ route('rop.managers.show', $manager) }}" class="btn btn-sm btn-cancel" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('rop.managers.edit', $manager) }}" class="btn btn-sm btn-save" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($manager->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-danger" title="Удалить"
                                    onclick="showDeleteModal('{{ $manager->id }}', '{{ $manager->name }}')">
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

            @if($managers instanceof \Illuminate\Contracts\Pagination\Paginator && $managers->hasPages())
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

<!-- Модальное окно удаления -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <h3 class="modal-title">Подтверждение удаления</h3>
            <p class="modal-subtitle">
                Вы действительно хотите удалить <strong id="deleteItemName"></strong>?
                Это действие нельзя отменить.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">
                <i class="fas fa-times"></i> Отмена
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-delete">
                    <i class="fas fa-trash"></i> Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* ===== админский стиль, применён к РОП ===== */
.edit-branch-container{max-width:1200px;margin:0 auto;padding:24px}
.page-header{margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--border-color)}
.header-content{display:flex;align-items:center;gap:16px}
.header-icon{width:48px;height:48px;background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--white);font-size:20px}
.page-title{font-size:28px;font-weight:700;color:var(--text-secondary);margin:0}
.page-subtitle{font-size:14px;color:var(--text-secondary);margin:4px 0 0 0}
.form-section{background:var(--bg-card);border-radius:12px;padding:24px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid var(--border-color)}
.section-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid var(--border-color);font-weight:600;font-size:16px;color:var(--text-primary)}
.section-header i{color:var(--white) !important;font-size:18px}
.section-actions{display:flex;gap:12px}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:linear-gradient(135deg,var(--bg-secondary) 0%,var(--bg-secondary) 100%);border:1px solid var(--border-color);border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;transition:.2s}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.1)}
.stat-icon{width:48px;height:48px;background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--white);font-size:20px}
.stat-number{font-size:16px;font-weight:600;color:var(--text-secondary);margin-bottom:4px}
.stat-label{font-size:12px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;font-weight:600}
.search-form .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px}
.form-group{position:relative}
.form-label{display:flex;align-items:center;gap:8px;font-weight:600;font-size:14px;color:var(--text-primary);margin-bottom:8px}
.form-label i{color:var(--text-secondary);font-size:14px}
.form-control{width:100%;padding:12px 16px;border:2px solid var(--border-color);border-radius:8px;font-size:14px;transition:.2s;background:#fafafa}
.form-control:focus{outline:none;border-color:#1ba4e9;background:var(--bg-card);box-shadow:0 0 0 3px rgba(27,164,233,.1)}
.form-actions{display:flex;gap:8px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;border:none;cursor:pointer;transition:.2s}
    .btn-sm{padding:8px 12px;font-size:12px}
    .btn-cancel{background:var(--bg-tertiary);color:var(--text-primary);border:1px solid #d1d5db}
    .btn-cancel:hover{background:var(--border-color);transform:translateY(-1px)}
    .btn-save{color:var(--white);}
.btn-save i{color:var(--white) !important;}
.btn-save{background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);color:var(--white);box-shadow:0 2px 4px rgba(27,164,233,.2)}
    .btn-save:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(27,164,233,.3)}
    .btn-danger{background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:var(--white);box-shadow:0 2px 4px rgba(239,68,68,.2)}
    .btn-danger:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(239,68,68,.3)}
    .personnel-section{display:flex;flex-direction:column;gap:16px}
.personnel-item{display:flex;align-items:flex-start;padding:16px;background:#fafafa;border-radius:8px;border:1px solid #f0f0f0;transition:.2s}
.personnel-item:hover{background:#f8f9fa;border-color:#e9ecef;transform:translateY(-1px)}
.personnel-icon{width:32px;height:32px;border-radius:6px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0}
.manager-item .personnel-icon{background:#f3e8ff;color:#7c3aed}
.personnel-content{flex:1;min-width:0}
.personnel-title{font-weight:600;font-size:16px;color:var(--text-secondary);margin-bottom:8px}
.personnel-list{display:flex;flex-wrap:wrap;gap:6px}
.personnel-tag{padding:4px 10px;border-radius:6px;font-size:12px;font-weight:500;display:inline-block;transition:.2s;border:1px solid}
.rop-tag{background:#eef2ff;color:#7c3aed;border:1px solid #c7d2fe}
.manager-tag{background:var(--bg-secondary);color:#475569;border:1px solid #cbd5e1}
.branch-tag{background:#f0f9ff;color:#0369a1;border-color:#bae6fd}
.contract-tag{background:#f0fdf4;color:#166534;border-color:#bbf7d0}
.amount-tag{background:#f0fdf4;color:#166534;border-color:#bbf7d0}
.month-tag{background:var(--bg-tertiary);color:var(--text-secondary);border-color:#d1d5db}
.email-tag{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
.tag-icon{margin-right:6px;opacity:.85}
.personnel-actions{display:flex;align-items:center;gap:8px;flex-shrink:0}
.empty-state{text-align:center;padding:48px 24px;color:var(--text-secondary)}
.empty-state i{font-size:48px;margin-bottom:16px;opacity:.5}
.pagination-container{display:flex;justify-content:center;margin-top:24px}
/* modal (админский стиль) */
.modal-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.6);z-index:99999;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.modal-content{background:var(--bg-card);border-radius:16px;padding:32px;max-width:450px;width:90%;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);border:1px solid rgba(0,0,0,.1);animation:modalSlideIn .3s ease-out}
@keyframes modalSlideIn{from{opacity:0;transform:translateY(-20px) scale(.95)}to{opacity:1;transform:translateY(0) scale(1)}}
.modal-header{text-align:center;margin-bottom:28px;display:inline}
.modal-icon{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);color:#d97706;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:24px;box-shadow:0 4px 12px rgba(217,119,6,.2)}
.modal-title{font-size:20px;font-weight:700;color:var(--text-secondary);margin-bottom:12px;line-height:1.3}
.modal-subtitle{color:var(--text-secondary);font-size:15px;line-height:1.6;margin:0}
.modal-actions{display:flex;gap:16px;justify-content:center;margin-top:32px}
.modal-btn{padding:12px 24px;border-radius:10px;font-weight:600;font-size:15px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:.2s;min-width:120px;justify-content:center}
.modal-btn-cancel{background:var(--bg-tertiary);color:var(--text-primary);border:1px solid var(--border-color)}
.modal-btn-cancel:hover{background:var(--border-color);color:var(--text-secondary);transform:translateY(-1px);box-shadow:0 4px 8px rgba(0,0,0,.1)}
.modal-btn-delete{background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:var(--white);box-shadow:0 4px 12px rgba(239,68,68,.3)}
.modal-btn-delete:hover{background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);transform:translateY(-1px);box-shadow:0 6px 16px rgba(239,68,68,.4)}
/* responsive */
@media (max-width:768px){
    .edit-branch-container{padding:16px}
    .stats-grid{grid-template-columns:repeat(2,1fr)}
    .search-form .form-grid{grid-template-columns:1fr}
    .section-header{flex-direction:column;align-items:flex-start;gap:16px}
    .personnel-item{flex-direction:column;gap:12px}
    .personnel-actions{align-self:flex-end}
}
</style>

<script>
function showDeleteModal(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = `/rop/managers/${id}`;
    document.getElementById('deleteModal').style.display = 'flex';
}
function hideDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) hideDeleteModal();
});
</script>
@endsection