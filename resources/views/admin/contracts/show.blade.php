@extends('layouts.admin')

@section('title', 'Просмотр договора')

@section('content')
<div class="page-wrapper">
    <div class="hero-card">
        <div class="hero-info">
            <div class="hero-icon"><i class="fas fa-file-contract"></i></div>
            <div>
                <h1>Договор №{{ $contract->contract_number }}</h1>
                <p>Детальная информация о договоре</p>
                <span class="status-pill {{ strtolower($contract->status ?? 'default') }}">
                    {{ $contract->status ?? 'Без статуса' }}
                </span>
            </div>
        </div>
        <div class="hero-actions">
            <button type="button" class="btn btn-danger"
                    onclick="showDeleteModal('{{ $contract->id }}', '{{ $contract->contract_number }}', 'contract')">
                <i class="fas fa-trash"></i><span>Удалить</span>
            </button>
            <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i><span>Редактировать</span>
            </a>
            <a href="{{ route('contracts.export-word', $contract) }}" class="btn btn-secondary">
                <i class="fas fa-file-word"></i><span>Экспорт в Word</span>
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
            <div class="stat-content">
                <div class="stat-label">Категория</div>
                <div class="stat-number">{{ $contract->category ?? '—' }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-content">
                <div class="stat-label">Общая сумма</div>
                <div class="stat-number">{{ number_format($contract->order_total) }} ₸</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-content">
                <div class="stat-label">Дата</div>
                <div class="stat-number">{{ $contract->date->format('d.m.Y') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user"></i></div>
            <div class="stat-content">
                <div class="stat-label">Менеджер</div>
                <div class="stat-number">{{ $contract->user->name }}</div>
            </div>
        </div>
    </div>

    <div class="actions-panel">
        <div class="panel-header">
            <h2>Действия с договором</h2>
            <p>Выберите действие для изменения статуса договора</p>
        </div>
        <div class="action-cards">
            <div class="action-card" onclick="showHistoryModal('{{ $contract->id }}', '{{ $contract->contract_number }}')">
                <div class="action-icon"><i class="fas fa-clock-rotate-left"></i></div>
                <div class="action-body">
                    <h3>История изменений</h3>
                    <p>Просмотреть все изменения и одобрения по договору</p>
                </div>
            </div>
            <div class="action-card">
                <div class="action-icon"><i class="fas fa-industry"></i></div>
                <div class="action-body">
                    <h3>Запустить производство</h3>
                    <p>Перевести договор в статус производства</p>
                </div>
            </div>
            <div class="action-card">
                <div class="action-icon"><i class="fas fa-search"></i></div>
                <div class="action-body">
                    <h3>Проверить качество</h3>
                    <p>Отправить заказ на контроль качества</p>
                </div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="section-header"><i class="fas fa-info-circle"></i><span>Основная информация</span></div>
        <div class="info-grid">
            <div class="info-block">
                <dl class="info-list">
                    <div class="info-row"><dt>Филиал</dt><dd>{{ $contract->branch->name }}</dd></div>
                    <div class="info-row"><dt>Модель</dt><dd>{{ $contract->model }}</dd></div>
                    <div class="info-row"><dt>Размеры</dt><dd>{{ $contract->width }} × {{ $contract->height }} мм</dd></div>
                    <div class="info-row"><dt>Способ оплаты</dt><dd>{{ $contract->payment ?? 'Не указан' }}</dd></div>
                </dl>
            </div>
            <div class="info-block">
                <dl class="info-list">
                    <div class="info-row"><dt>Категория</dt><dd>{{ $contract->category ?? '—' }}</dd></div>
                    <div class="info-row"><dt>Создан</dt><dd>{{ $contract->created_at->format('d.m.Y H:i') }}</dd></div>
                    <div class="info-row"><dt>Обновлён</dt><dd>{{ $contract->updated_at->format('d.m.Y H:i') }}</dd></div>
                    <div class="info-row"><dt>Ответственный</dt><dd>{{ $contract->user->name }}</dd></div>
                </dl>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="section-header"><i class="fas fa-user"></i><span>Информация о клиенте</span></div>
        <div class="info-grid">
            <div class="info-block">
                <dl class="info-list">
                    <div class="info-row"><dt>ФИО</dt><dd>{{ $contract->client }}</dd></div>
                    <div class="info-row"><dt>ИИН</dt><dd>{{ $contract->iin }}</dd></div>
                    <div class="info-row"><dt>Instagram</dt><dd>{{ $contract->instagram }}</dd></div>
                    <div class="info-row"><dt>Адрес</dt><dd>{{ $contract->address ?? '—' }}</dd></div>
                </dl>
            </div>
            <div class="info-block">
                <dl class="info-list">
                    <div class="info-row"><dt>Телефон</dt><dd>{{ $contract->phone }}</dd></div>
                    <div class="info-row"><dt>Доп. телефон</dt><dd>{{ $contract->phone2 ?? '—' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="section-header"><i class="fas fa-cogs"></i><span>Технические характеристики</span></div>
        <div class="info-grid">
            <div class="info-block">
                <dl class="info-list">
                    <div class="info-row"><dt>Внешняя панель</dt><dd>{{ $contract->outer_panel }}</dd></div>
                    <div class="info-row"><dt>Внешняя обшивка</dt><dd>{{ $contract->outer_cover }}</dd></div>
                    <div class="info-row"><dt>Внутренняя обшивка</dt><dd>{{ $contract->inner_cover }}</dd></div>
                    <div class="info-row"><dt>Внутренняя отделка</dt><dd>{{ $contract->inner_trim }}</dd></div>
                </dl>
            </div>
            <div class="info-block">
                <dl class="info-list">
                    <div class="info-row"><dt>Стеклопакет</dt><dd>{{ $contract->glass_unit }}</dd></div>
                    <div class="info-row"><dt>Замок</dt><dd>{{ $contract->lock }}</dd></div>
                    <div class="info-row"><dt>Ручка</dt><dd>{{ $contract->handle }}</dd></div>
                    <div class="info-row"><dt>Толщина стали</dt><dd>{{ $contract->steel_thickness }} мм</dd></div>
                </dl>
            </div>
        </div>
    </div>

    @if($contract->extra)
    <div class="info-section">
        <div class="section-header"><i class="fas fa-plus-circle"></i><span>Дополнительно</span></div>
        <p class="text-body">{{ $contract->extra }}</p>
    </div>
    @endif

    <div class="info-section">
        <div class="section-header"><i class="fas fa-money-bill"></i><span>Финансовая информация</span></div>
        <ul class="finance-list">
            <li><span>Общая сумма</span><strong>{{ number_format($contract->order_total) }} ₸</strong></li>
            <li><span>Предоплата</span><strong>{{ number_format($contract->order_deposit) }} ₸</strong></li>
            <li><span>Остаток</span><strong>{{ number_format($contract->order_remainder) }} ₸</strong></li>
            <li class="to-pay"><span>К оплате</span><strong>{{ number_format($contract->order_due) }} ₸</strong></li>
        </ul>
    </div>

    @if($contract->photo_path || $contract->attachment_path)
    <div class="info-section">
        <div class="section-header"><i class="fas fa-paperclip"></i><span>Файлы</span></div>
        <div class="file-actions">
            @if($contract->photo_path)
                <a href="{{ Storage::url($contract->photo_path) }}" target="_blank" class="btn btn-secondary">
                    <i class="fas fa-image"></i><span>Фото двери</span>
                </a>
            @endif
            @if($contract->attachment_path)
                <a href="{{ Storage::url($contract->attachment_path) }}" target="_blank" class="btn btn-secondary">
                    <i class="fas fa-file"></i><span>Скачать вложение</span>
                </a>
            @endif
        </div>
    </div>
    @endif
</div>

<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h3 class="modal-title">Подтверждение удаления</h3>
        <p class="modal-subtitle">Вы действительно хотите удалить договор <strong id="deleteItemName"></strong>? Это действие нельзя отменить.</p>
        <div class="modal-actions">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">
                <i class="fas fa-times"></i><span>Отмена</span>
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-delete">
                    <i class="fas fa-trash"></i><span>Удалить</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
function showDeleteModal(id, name, type) {
    document.getElementById('deleteItemName').textContent = name;

    let baseUrl = '';
    @if(Auth::user()->role === 'admin')
        baseUrl = '{{ url("/admin/contracts") }}';
    @elseif(Auth::user()->role === 'manager')
        baseUrl = '{{ url("/manager/contracts") }}';
    @elseif(Auth::user()->role === 'rop')
        baseUrl = '{{ url("/rop/contracts") }}';
    @else
        baseUrl = '{{ url("/contracts") }}';
    @endif

    document.getElementById('deleteForm').action = type === 'contract' ? `${baseUrl}/${id}` : '';
    document.getElementById('deleteModal').style.display = 'flex';
}
function hideDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) hideDeleteModal();
});
</script>

<style>
.page-wrapper { display:flex; flex-direction:column; gap:24px; }
.hero-card { background:var(--bg-card); border-radius:24px; padding:24px 28px; border:1px solid rgba(148,163,184,0.08); display:flex; align-items:flex-start; justify-content:space-between; gap:24px; box-shadow:var(--shadow-sm); }
.hero-info { display:flex; gap:18px; align-items:flex-start; }
.hero-icon { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; background:rgba(27,164,233,0.18); color:#7cc7f5; }
.hero-info h1 { margin:0; font-size:26px; font-weight:700; color:var(--text-primary); }
.hero-info p { margin:6px 0 12px; color:var(--text-secondary); }
.status-pill { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:999px; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; background:rgba(148,163,184,0.12); border:1px solid rgba(148,163,184,0.18); color:var(--text-secondary); }
.status-pill.approved { background:rgba(16,185,129,0.18); border-color:rgba(16,185,129,0.3); color:#7ce0b9; }
.status-pill.pending { background:rgba(245,158,11,0.18); border-color:rgba(245,158,11,0.3); color:#fcd38b; }
.hero-actions { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
.btn { display:inline-flex; align-items:center; gap:8px; border:none; cursor:pointer; padding:12px 18px; border-radius:12px; font-weight:600; font-size:14px; transition:transform .2s ease, box-shadow .2s ease, background .2s ease; }
.btn span { display:inline-block; }
.btn:hover { transform:translateY(-1px); box-shadow:var(--shadow-md); }
.btn-primary { background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); color:#fff; }
.btn-danger { background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%); color:#fff; }
.btn-secondary { background:linear-gradient(135deg,#0ea5e9 0%,#22d3ee 100%); color:#fff; }
.stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
.stat-card { background:var(--bg-card); border:1px solid rgba(148,163,184,0.12); border-radius:18px; padding:20px; display:flex; gap:16px; align-items:center; transition:transform .2s ease, box-shadow .2s ease; }
.stat-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
.stat-icon { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:20px; background:rgba(59,130,246,0.15); color:#82a5ff; }
.stat-number { font-size:18px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
.stat-label { font-size:12px; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-secondary); }
actions-panel { }
.actions-panel { background:var(--bg-card); border-radius:20px; border:1px solid rgba(148,163,184,0.08); padding:24px; box-shadow:var(--shadow-sm); display:flex; flex-direction:column; gap:20px; }
.panel-header h2 { margin:0; font-size:20px; color:var(--text-primary); }
.panel-header p { margin:6px 0 0; color:var(--text-secondary); font-size:14px; }
.action-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
.action-card { background:var(--bg-secondary); border:1px solid rgba(148,163,184,0.12); border-radius:18px; padding:20px; display:flex; gap:16px; align-items:flex-start; cursor:pointer; transition:transform .2s ease, box-shadow .2s ease, border .2s ease; }
.action-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); border-color:rgba(59,130,246,0.3); }
.action-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; background:rgba(59,130,246,0.18); color:#9db7ff; }
.action-body h3 { margin:0 0 6px; font-size:16px; color:var(--text-primary); }
.action-body p { margin:0; color:var(--text-secondary); font-size:13px; }
.info-section { background:var(--bg-card); border-radius:16px; padding:24px; border:1px solid rgba(148,163,184,0.08); box-shadow:var(--shadow-sm); display:flex; flex-direction:column; gap:18px; }
.section-header { display:flex; align-items:center; gap:12px; font-size:16px; font-weight:600; color:var(--text-primary); }
.section-header i { color:#7cc7f5; }
.info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:18px; }
.info-block { background:var(--bg-secondary); border:1px solid rgba(148,163,184,0.12); padding:18px 20px; border-radius:18px; }
.info-row { display:flex; justify-content:space-between; gap:12px; padding:10px 0; border-bottom:1px dashed rgba(148,163,184,0.14); }
.info-row:last-child { border-bottom:none; }
.info-row dt { width:45%; color:var(--text-secondary); font-weight:600; text-transform:uppercase; font-size:12px; letter-spacing:0.05em; }
.info-row dd { margin:0; color:var(--text-primary); font-size:14px; font-weight:500; text-align:right; }
.text-body { margin:0; color:var(--text-primary); line-height:1.6; }
.finance-list { list-style:none; padding:0; margin:0; display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px; }
.finance-list li { background:var(--bg-secondary); border-radius:14px; border:1px solid rgba(148,163,184,0.12); padding:18px 20px; display:flex; flex-direction:column; gap:6px; transition:transform .2s ease, box-shadow .2s ease; }
.finance-list li:hover { transform:translateY(-2px); box-shadow:var(--shadow-sm); }
.finance-list span { color:var(--text-secondary); font-size:12px; letter-spacing:0.08em; text-transform:uppercase; }
.finance-list strong { color:var(--text-primary); font-size:18px; font-weight:700; }
.finance-list li.to-pay { background:rgba(59,130,246,0.12); border-color:rgba(59,130,246,0.25); }
.file-actions { display:flex; flex-wrap:wrap; gap:12px; }
.modal-overlay { display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.78); backdrop-filter:blur(6px); align-items:center; justify-content:center; }
.modal-content { background:var(--bg-card); border-radius:20px; padding:30px 32px; border:1px solid rgba(148,163,184,0.12); width:90%; max-width:420px; text-align:center; box-shadow:0 30px 60px -20px rgba(0,0,0,0.4); }
.modal-icon { width:60px; height:60px; border-radius:16px; margin:0 auto 18px; display:flex; align-items:center; justify-content:center; font-size:24px; background:rgba(239,68,68,0.18); color:#fca5a5; }
.modal-title { margin:0 0 8px; font-size:20px; font-weight:700; color:var(--text-primary); }
.modal-subtitle { margin:0; color:var(--text-secondary); line-height:1.6; }
.modal-actions { display:flex; gap:12px; justify-content:center; margin-top:24px; flex-wrap:wrap; }
.modal-btn { display:inline-flex; align-items:center; gap:6px; padding:12px 20px; border-radius:12px; border:none; cursor:pointer; font-weight:600; font-size:14px; transition:transform .2s ease, box-shadow .2s ease; }
.modal-btn:hover { transform:translateY(-1px); box-shadow:var(--shadow-sm); }
.modal-btn-cancel { background:var(--bg-secondary); color:var(--text-primary); border:1px solid rgba(148,163,184,0.12); }
.modal-btn-delete { background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%); color:#fff; }
@media (max-width: 768px) {
    .hero-card { flex-direction:column; }
    .hero-actions { width:100%; }
    .hero-actions .btn { flex:1; justify-content:center; }
}
</style>