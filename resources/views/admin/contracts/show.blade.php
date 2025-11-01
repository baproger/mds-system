@extends('layouts.admin')

@section('title', 'Просмотр договора')

@section('content')
<div class="edit-branch-container">
    <!-- Заголовок -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Просмотр договора</h1>
                <p class="page-subtitle">Договор № {{ $contract->contract_number }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('contracts.export-word', $contract) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Экспорт Word
            </a>
            <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Редактировать
            </a>
            <button type="button" class="btn btn-danger"
                    onclick="showDeleteModal('{{ $contract->id }}', '{{ $contract->contract_number }}', 'contract')">
                <i class="fas fa-trash"></i> Удалить
            </button>
        </div>
    </div>

    <!-- Контент -->
    <div class="content-grid">
        <div class="content-main">
            <!-- Основная информация -->
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    <span>Основная информация</span>
                </div>
                <div class="info-grid">
                    <div class="info-block">
                        <dl class="info-list">
                            <div class="info-row">
                                <dt>Номер договора</dt>
                                <dd><span class="badge badge-primary">{{ $contract->contract_number }}</span></dd>
                            </div>
                            <div class="info-row">
                                <dt>Дата</dt>
                                <dd>{{ $contract->date->format('d.m.Y') }}</dd>
                            </div>
                            <div class="info-row">
                                <dt>Филиал</dt>
                                <dd>{{ $contract->branch->name }}</dd>
                            </div>
                            <div class="info-row">
                                <dt>Менеджер</dt>
                                <dd>{{ $contract->user->name }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="info-block">
                        <dl class="info-list">
                            <div class="info-row">
                                <dt>Категория</dt>
                                <dd>
                                    <span class="badge {{ $contract->category === 'Lux' ? 'badge-danger' : ($contract->category === 'Premium' ? 'badge-warning' : 'badge-success') }}">
                                        {{ $contract->category }}
                                    </span>
                                </dd>
                            </div>
                            <div class="info-row">
                                <dt>Модель</dt>
                                <dd>{{ $contract->model }}</dd>
                            </div>
                            <div class="info-row">
                                <dt>Размеры</dt>
                                <dd>{{ $contract->width }} × {{ $contract->height }} мм</dd>
                            </div>
                            <div class="info-row">
                                <dt>Способ оплаты</dt>
                                <dd>{{ $contract->payment ?? 'Не указан' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Информация о клиенте -->
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-user"></i>
                    <span>Информация о клиенте</span>
                </div>
                <div class="info-grid">
                    <div class="info-block">
                        <dl class="info-list">
                            <div class="info-row"><dt>ФИО</dt><dd>{{ $contract->client }}</dd></div>
                            <div class="info-row"><dt>ИИН</dt><dd>{{ $contract->iin }}</dd></div>
                            <div class="info-row"><dt>Instagram</dt><dd>{{ $contract->instagram }}</dd></div>
                            @if($contract->address)
                                <div class="info-row"><dt>Адрес</dt><dd>{{ $contract->address }}</dd></div>
                            @endif
                        </dl>
                    </div>
                    <div class="info-block">
                        <dl class="info-list">
                            <div class="info-row"><dt>Телефон</dt><dd>{{ $contract->phone }}</dd></div>
                            <div class="info-row"><dt>Доп. телефон</dt><dd>{{ $contract->phone2 }}</dd></div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Технические характеристики -->
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-cogs"></i>
                    <span>Технические характеристики</span>
                </div>
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

            <!-- Дополнительно -->
            @if($contract->extra)
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-plus-circle"></i>
                    <span>Дополнительно</span>
                </div>
                <p class="text-body">{{ $contract->extra }}</p>
            </div>
            @endif
        </div>

        <!-- Боковая колонка -->
        <aside class="content-aside">
            <!-- Финансы -->
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-money-bill"></i>
                    <span>Финансовая информация</span>
                </div>
                <ul class="finance-list">
                    <li><span>Общая сумма</span><strong>{{ number_format($contract->order_total) }} ₸</strong></li>
                    <li><span>Предоплата</span><strong>{{ number_format($contract->order_deposit) }} ₸</strong></li>
                    <li><span>Остаток</span><strong>{{ number_format($contract->order_remainder) }} ₸</strong></li>
                    <li class="to-pay"><span>К оплате</span><strong>{{ number_format($contract->order_due) }} ₸</strong></li>
                </ul>
            </div>

            <!-- Файлы -->
            @if($contract->photo_path || $contract->attachment_path)
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-paperclip"></i>
                    <span>Файлы</span>
                </div>
                <div class="file-actions">
                    @if($contract->photo_path)
                        <a href="{{ Storage::url($contract->photo_path) }}" target="_blank" class="btn btn-light">
                            <i class="fas fa-image"></i> Просмотреть фото
                        </a>
                    @endif
                    @if($contract->attachment_path)
                        <a href="{{ Storage::url($contract->attachment_path) }}" target="_blank" class="btn btn-light">
                            <i class="fas fa-file"></i> Скачать вложение
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Действия -->
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-tools"></i>
                    <span>Действия</span>
                </div>
                <div class="stacked-actions">
                    <a href="{{ route('contracts.export-word', $contract) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Экспорт в Word
                    </a>
                    <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                    <button type="button" class="btn btn-danger"
                            onclick="showDeleteModal('{{ $contract->id }}', '{{ $contract->contract_number }}', 'contract')">
                        <i class="fas fa-trash"></i> Удалить
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>

<!-- Модальное окно удаления -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="modal-title">Подтверждение удаления</h3>
            <p class="modal-subtitle">
                Вы действительно хотите удалить договор <strong id="deleteItemName"></strong>? Это действие нельзя отменить.
            </p>
        </div>
        <div class="modal-actions">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">
                <i class="fas fa-times"></i> Отмена
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-delete">
                    <i class="fas fa-trash"></i> Удалить
                </button>
            </form>
        </div>
    </div>
</div>

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
/* ===== ЕДИНЫЙ СТИЛЬ — как на остальных страницах ===== */
.edit-branch-container { max-width: 1200px; margin: 0 auto; padding: 24px; }

.page-header {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:32px; padding-bottom:24px; border-bottom:1px solid var(--border-color);
}
.header-content { display:flex; align-items:center; gap:16px; }
.header-icon { width:48px; height:48px; background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);
    border-radius:12px; display:flex; align-items:center; justify-content:center; color:var(--white); font-size:20px; }
.page-title { font-size:28px; font-weight:700; color:var(--text-secondary); margin:0; }
.page-subtitle { font-size:14px; color:var(--text-secondary); margin:4px 0 0 0; }
.header-actions { display:flex; gap:12px; flex-wrap:wrap; }

.content-grid {
    display:grid; grid-template-columns: 2fr 1fr; gap:24px;
}
.content-main { display:flex; flex-direction:column; gap:24px; }
.content-aside { display:flex; flex-direction:column; gap:24px; }

/* Секции (как form-section) */
.info-section {
    background:var(--bg-card); border-radius:12px; padding:24px; border:1px solid var(--border-color);
    box-shadow:0 1px 3px rgba(0,0,0,.1); animation:fadeIn .3s ease-out;
}
.section-header {
    display:flex; align-items:center; gap:12px; margin-bottom:16px; padding-bottom:12px;
    border-bottom:2px solid var(--border-color); font-weight:600; font-size:16px; color:var(--text-primary);
}
.section-header i { color:#1ba4e9; font-size:18px; }

/* Двухколоночные блоки внутри секции */
.info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:16px; }
.info-list { margin:0; }
.info-row { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px dashed #f1f5f9; }
.info-row:last-child { border-bottom:none; }
.info-row dt { width:45%; min-width:180px; color:var(--text-secondary); font-weight:600; }
.info-row dd { margin:0; color:var(--text-secondary); }

/* Финансы */
.finance-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; }
.finance-list li { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border:1px solid var(--border-color); border-radius:10px; background:#fafafa; }
.finance-list li.to-pay { background:#eef2ff; border-color:#e0e7ff; }
.finance-list span { color:var(--text-secondary); }
.finance-list strong { color:var(--text-secondary); }

/* Файлы и действия */
.file-actions { display:flex; flex-direction:column; gap:10px; }
.stacked-actions { display:flex; flex-direction:column; gap:12px; }

/* Кнопки */
.btn {
    display:inline-flex; align-items:center; gap:8px; padding:12px 20px;
    border-radius:8px; font-weight:600; font-size:14px; text-decoration:none; border:none;
    cursor:pointer; transition:.2s;
}
.btn:hover { transform:translateY(-1px); }
.btn-primary { background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%); color:var(--white);
    box-shadow:0 2px 4px rgba(27,164,233,.2); }
.btn-success { background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:var(--white);
    box-shadow:0 2px 4px rgba(16,185,129,.2); }
.btn-danger { background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%); color:var(--white);
    box-shadow:0 2px 4px rgba(239,68,68,.2); }
.btn-light { background:#f3f4f6; color:var(--text-primary); border:1px solid var(--border-color); }
.btn-light:hover { background:#e5e7eb; }

/* Бейджи */
.badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; color:var(--white); }
.badge-primary { background:#3b82f6; }
.badge-success { background:#10b981; }
.badge-warning { background:#f59e0b; }
.badge-danger  { background:#ef4444; }

/* Анимация */
@keyframes fadeIn { from {opacity:0; transform:translateY(10px)} to {opacity:1; transform:translateY(0)} }

/* Модалка — как на других страницах */
.modal-overlay {
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.6); backdrop-filter:blur(4px);
    align-items:center; justify-content:center;
}
.modal-content {
    background:var(--bg-card); border-radius:16px; padding:32px; width:90%; max-width:460px;
    box-shadow:0 25px 50px -12px rgba(0,0,0,.25); border:1px solid rgba(0,0,0,.1);
    animation:modalSlideIn .3s ease-out;
}
@keyframes modalSlideIn { from {opacity:0; transform:translateY(-20px) scale(.95)} to {opacity:1; transform:translateY(0) scale(1)} }
.modal-header { text-align:center; margin-bottom:24px; }
.modal-icon {
    width:56px; height:56px; border-radius:50%;
    background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%); color:#d97706;
    display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:24px;
    box-shadow:0 4px 12px rgba(217,119,6,.2);
}
.modal-title { font-size:20px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; }
.modal-subtitle { color:var(--text-secondary); font-size:15px; margin:0; line-height:1.6; }
.modal-actions { display:flex; gap:12px; justify-content:center; margin-top:24px; }
.modal-btn {
    padding:12px 24px; border-radius:10px; font-weight:600; font-size:15px; border:none;
    cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:.2s; min-width:120px; justify-content:center;
}
.modal-btn-cancel { background:#f3f4f6; color:var(--text-primary); border:1px solid var(--border-color); }
.modal-btn-cancel:hover { background:#e5e7eb; }
.modal-btn-delete { background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%); color:var(--white); }
.modal-btn-delete:hover { background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%); }

/* Адаптив */
@media (max-width: 1024px) {
  .content-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .edit-branch-container { padding:16px; }
  .page-header { flex-direction:column; align-items:flex-start; gap:16px; }
  .header-actions { width:100%; gap:8px; }
  .header-actions .btn { flex:1; justify-content:center; }
}
</style>
@endsection