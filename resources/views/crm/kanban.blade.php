@extends('layouts.admin')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('title', 'Канбан-доска')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
            <div class="header-icon"><i class="fa-solid fa-columns"></i></div>
                        <div class="header-text">
              <h1 class="page-title">Канбан доска</h1>
              <p class="page-subtitle">Управление сделками и заявками в реальном времени</p>
                        </div>
                    </div>
                </div>

        <div class="kanban-columns-container">
          <div class="kanban-columns">
            @foreach([
              'draft'          => ['icon' => 'fa-regular fa-file-lines', 'name' => 'Новая заявка'],
              'pending_rop'    => ['icon' => 'fa-solid fa-user-check',  'name' => 'На рассмотрении'],
              'approved'       => ['icon' => 'fa-solid fa-circle-check','name' => 'Одобрено'],
              'rejected'       => ['icon' => 'fa-solid fa-circle-xmark','name' => 'Отклонено'],
              'on_hold'        => ['icon' => 'fa-solid fa-circle-pause','name' => 'Приостановлено'],
              'in_production'  => ['icon' => 'fa-solid fa-gears',       'name' => 'В работе'],
              'quality_check'  => ['icon' => 'fa-solid fa-clipboard-check','name' => 'Проверка'],
              'ready'          => ['icon' => 'fa-solid fa-truck-fast',  'name' => 'Готово'],
              'shipped'        => ['icon' => 'fa-solid fa-truck',       'name' => 'Отправлено'],
              'completed'      => ['icon' => 'fa-solid fa-flag-checkered','name' => 'Завершено'],
              'returned'       => ['icon' => 'fa-solid fa-arrow-rotate-left','name' => 'На доработке'],
            ] as $status => $column)
              <div class="form-section kanban-column-section" data-status="{{ $status }}">
                    <div class="section-header">
                  <i class="{{ $column['icon'] }}"></i>
                  <span>{{ $column['name'] }}</span>
                  <div class="column-stats">{{ count($contractsByStatus[$status] ?? []) }}</div>
                    </div>
                    
                <div class="column-content">
                  @foreach(($contractsByStatus[$status] ?? []) as $contract)
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" draggable="true">
                      <div class="card-header">
                        <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        
                      <div class="card-meta">
                        <div class="card-time">{{ $contract->created_at->format('d.m.Y H:i') }}</div>
                        <div class="card-status">
                          <div class="status-dot {{ $contract->status }}"></div>
                          <span class="status-text">{{ \App\Models\Contract::getStatusLabel($contract->status) }}</span>
                        </div>
                        </div>
                        
                      <div class="card-progress">
                        <div class="progress">
                          <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                        </div>
                        <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                    </div>
                    
                      <div class="card-manager">
                        <i class="fa-solid fa-user manager-icon"></i>
                        <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                            </div>
                            
                      @can('deals.view-amounts')
                        <div class="card-amount-bottom">
                          {{ number_format($contract->order_total ?? 0, 0, ',', ' ') }} 〒
                                        </div>
                      @endcan

                      <div class="card-actions">
                        <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                          <i class="fa-regular fa-eye"></i>
                        </a>
                        <button class="btn-action" title="Позвонить">
                          <i class="fa-solid fa-phone"></i>
                        </button>
                        <button class="btn-action" title="Сообщение">
                          <i class="fa-regular fa-comment-dots"></i>
                        </button>
                                        </div>
                                    </div>
                                    @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
:root{
  --bg: #f7f8fa;
  --card: #ffffff;
  --text: #111827;
  --muted: #6b7280;
  --border: #e5e7eb;
  --subtle: #f3f4f6;
  --accent: #2563eb;
  --accent-2: #8b5cf6;
  --success: #10b981;
  --danger: #ef4444;
  --shadow: 0 1px 2px rgba(0,0,0,.06);
  --shadow-lg: 0 8px 24px rgba(0,0,0,.08);
}
body{ background:var(--bg); color:var(--text); }
.container-fluid{ background:var(--bg); }

.kanban-columns-container{ overflow-x:auto; margin:0 -24px; padding:0 24px 24px; }
.kanban-columns{ display:flex; gap:16px; min-height:600px; }

.kanban-column-section{
  min-width:300px; max-width:300px; flex-shrink:0; margin-bottom:0;
  padding:16px; background:var(--card); border-radius:12px;
  box-shadow:var(--shadow); border:1px solid var(--border);
}
.kanban-column-section .section-header{
  margin-bottom:12px; padding-bottom:10px; border-bottom:1px solid var(--border);
  display:flex; align-items:center; justify-content:space-between; gap:8px;
}
.kanban-column-section .section-header i{ color:var(--accent); font-size:18px; }
.section-header span{ font:600 14px/1.2 system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji","Segoe UI Emoji"; }
.column-stats{ background:var(--subtle); color:var(--muted); padding:4px 8px; border-radius:999px; font:600 12px/1 system-ui; }

.column-content{ min-height:140px; max-height:70vh; overflow-y:auto; padding-bottom:8px; }
.column-content:empty{ border:1px dashed var(--border); border-radius:8px; padding:14px; min-height:160px; background:var(--subtle); }
.column-content:empty::after{ content:"Перетащите договор сюда"; display:block; text-align:center; color:var(--muted); font-size:12px; }

.contract-card{
  background:var(--card); border-radius:10px; padding:12px; margin-bottom:12px;
  box-shadow:var(--shadow); border:1px solid var(--border);
  transition:transform .15s ease, box-shadow .2s ease, border-color .2s ease; cursor:grab;
}
.contract-card:hover{ transform:translateY(-2px); box-shadow:var(--shadow-lg); border-color:var(--subtle); }
.contract-card:active{ cursor:grabbing; }

.card-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; padding-bottom:8px; border-bottom:1px solid var(--border); }
.card-number{ color:var(--text); font:600 14px/1 system-ui; }

.card-meta{ display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
.card-time{ color:var(--muted); font-size:11px; }
.card-status{ display:flex; align-items:center; gap:6px; }
.status-dot{ width:8px; height:8px; border-radius:50%; background:var(--success); box-shadow:0 0 0 2px rgba(0,0,0,0); }
.status-dot.draft{ background:#6b7280; }
.status-dot.pending_rop{ background:#f59e0b; }
.status-dot.approved{ background:#10b981; }
.status-dot.rejected{ background:#ef4444; }
.status-dot.on_hold{ background:#8b5cf6; }
.status-dot.in_production{ background:#3b82f6; }
.status-dot.quality_check{ background:#06b6d4; }
.status-dot.ready{ background:#84cc16; }
.status-dot.shipped{ background:#f97316; }
.status-dot.completed{ background:#059669; }
.status-dot.returned{ background:#6b7280; }
.status-text{ color:var(--muted); font-size:11px; }

.card-progress{ margin-bottom:8px; }
.progress{ background:var(--subtle); border-radius:4px; height:6px; overflow:hidden; }
.progress-bar{ background:linear-gradient(90deg, var(--accent) 0%, var(--accent-2) 100%); height:100%; border-radius:4px; transition:width .3s ease; }
.progress-text{ color:var(--muted); font-size:11px; margin-top:4px; }

.card-manager{ display:flex; align-items:center; gap:6px; margin-bottom:8px; }
.manager-icon{ color:var(--muted); font-size:12px; }
.manager-name{ color:var(--text); font:500 12px/1 system-ui; }
.card-amount-bottom{ color:#059669; font-weight:700; font-size:14px; margin-bottom:8px; }

.card-actions{ display:flex; gap:6px; }
.btn-action{ background:var(--subtle); border:1px solid var(--border); border-radius:6px; padding:6px; color:var(--muted); text-decoration:none; transition:all .15s ease; display:flex; align-items:center; justify-content:center; min-width:28px; height:28px; cursor:pointer; }
.btn-action:hover{ background:var(--accent); color:#fff; border-color:var(--accent); transform:translateY(-1px); }

.notification{ position:fixed; top:20px; right:20px; background:var(--success); color:#fff; padding:12px 20px; border-radius:8px; box-shadow:var(--shadow-lg); z-index:10000; opacity:0; transform:translateY(-20px); transition:all .3s ease; }
.notification.show{ opacity:1; transform:translateY(0); }
.notification.error{ background:var(--danger); }

/* Drag styles */
.sortable-ghost{ opacity:.35; background:var(--subtle); border:2px dashed var(--border); transform:rotate(1deg); }
.sortable-chosen{ transform:scale(1.02); box-shadow:var(--shadow-lg); z-index:1000; cursor:grabbing !important; }
.sortable-drag{ opacity:.95; transform:rotate(1deg) scale(1.03); box-shadow:var(--shadow-lg); z-index:1001; }

/* Animations & responsive */
@keyframes cardSlideIn{ from{opacity:0; transform:translateY(10px);} to{opacity:1; transform:translateY(0);} }
@media (max-width:1024px){ .kanban-column-section{ min-width:280px; max-width:280px; } }
@media (max-width:768px){
  .edit-branch-container{ padding:16px; }
  .kanban-columns-container{ margin:0 -16px; padding:0 16px 16px; }
  .kanban-column-section{ min-width:260px; max-width:260px; padding:12px; }
  .card-actions{ flex-wrap:wrap; gap:4px; }
  .btn-action{ min-width:24px; height:24px; padding:4px; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  setupSortable();
  updateColumnStats();
  setupWheelHorizontalScroll();
});

function setupWheelHorizontalScroll(){
  const scroller = document.querySelector('.kanban-columns-container');
  if(!scroller) return;
  scroller.addEventListener('wheel', (e)=>{
    if(Math.abs(e.deltaY) > Math.abs(e.deltaX)){
      scroller.scrollLeft += e.deltaY;
      e.preventDefault();
    }
  }, {passive:false});
}

function setupSortable() {
  const columns = document.querySelectorAll('.kanban-column-section');
    columns.forEach(column => {
    const list = column.querySelector('.column-content');
    if (!list) return;

    new Sortable(list, {
            group: 'contracts',
            animation: 150,
      ghostClass: 'sortable-ghost',
      chosenClass: 'sortable-chosen',
      dragClass: 'sortable-drag',
      forceFallback: false,
      swapThreshold: 0.5,
      onStart: function(evt) {
        document.body.style.cursor = 'grabbing';
        evt.item.style.opacity = '0.8';
        evt.item.style.transform = 'rotate(2deg) scale(1.05)';
        evt.item.style.zIndex = '1000';
      },
            onEnd: function(evt) {
        document.body.style.cursor = '';
        evt.item.style.opacity = '1';
        evt.item.style.transform = '';
        evt.item.style.zIndex = '';
        
        const item = evt.item;
        const contractId = item.dataset.contractId;
        const oldStatus = evt.from.closest('.kanban-column-section')?.dataset.status;
        const newStatus = evt.to.closest('.kanban-column-section')?.dataset.status;

        // Если перемещение в ту же колонку или нет ID контракта
        if (!contractId || oldStatus === newStatus) {
          updateColumnStats();
          return;
        }

        // Обновляем статус
        updateContractStatus(contractId, newStatus, item, oldStatus);
      }
    });
});
}

/* Обновление статуса контракта */
async function updateContractStatus(contractId, newStatus, item, oldStatus) {
  try {
    // Показываем индикатор загрузки
    item.style.opacity = '0.6';
    item.style.pointerEvents = 'none';
    
    const response = await fetch(`{{ route(Auth::user()->role . '.crm.update-status', ['contract' => 'CONTRACT_ID']) }}`.replace('CONTRACT_ID', contractId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ status: newStatus })
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }

    const data = await response.json();
    
    if (data.success) {
      // Обновляем UI карточки
      applyCardStatusUI(item, newStatus);
      updateColumnStats();
      showNotification('Статус обновлен!', 'success');
    } else {
      throw new Error(data.message || 'Ошибка обновления');
    }
  } catch (error) {
    console.error('Ошибка обновления статуса:', error);
    
    // Возвращаем карточку в исходную колонку
    const oldColumn = document.querySelector(`[data-status="${oldStatus}"] .column-content`);
    if (oldColumn) {
      oldColumn.appendChild(item);
    }
    
    // Восстанавливаем UI
    applyCardStatusUI(item, oldStatus);
    updateColumnStats();
    showNotification('Ошибка: ' + error.message, 'error');
  } finally {
    item.style.opacity = '1';
    item.style.pointerEvents = '';
  }
}

function statusLabel(status){
  const map = {
    draft:'Новая заявка', 
    pending_rop:'На рассмотрении', 
    approved:'Одобрено',
    rejected:'Отклонено', 
    on_hold:'Приостановлено', 
    in_production:'В работе',
    quality_check:'Проверка', 
    ready:'Готово', 
    shipped:'Отправлено', 
    completed:'Завершено', 
    returned:'На доработке'
  };
  return map[status] || status;
}

function getProgressForStatus(status){
  const progressMap = {
    draft: 10,
    pending_rop: 25,
    approved: 40,
    rejected: 0,
    on_hold: 30,
    in_production: 50,
    quality_check: 70,
    ready: 85,
    shipped: 95,
    completed: 100,
    returned: 20
  };
  return progressMap[status] || 0;
}

function applyCardStatusUI(cardEl, status){
  // Обновляем точку статуса
  const dot = cardEl.querySelector('.status-dot');
  if (dot) {
    dot.className = 'status-dot ' + status;
  }
  
  // Обновляем текст статуса
  const txt = cardEl.querySelector('.status-text');
  if (txt) {
    txt.textContent = statusLabel(status);
  }
  
  // Обновляем прогресс-бар в зависимости от статуса
  const progressBar = cardEl.querySelector('.progress-bar');
  if (progressBar) {
    const progress = getProgressForStatus(status);
    
    // Плавная анимация изменения прогресса
    progressBar.style.transition = 'width 0.5s ease-in-out';
    progressBar.style.width = progress + '%';
    
    const progressText = cardEl.querySelector('.progress-text');
    if (progressText) {
      progressText.textContent = progress + '% выполнено';
    }
  }
  
  // Обновляем атрибут data-status для карточки
  cardEl.setAttribute('data-status', status);
}

async function apiUpdateStatus(contractId, newStatus){
  const url = `{{ route(Auth::user()->role . '.crm.update-status', ['contract' => 'CONTRACT_ID']) }}`.replace('CONTRACT_ID', contractId);
  const res = await fetch(url, {
    method:'POST',
    headers:{
      'Content-Type':'application/json',
      'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body:JSON.stringify({ status:newStatus })
  });
  if (!res.ok) throw new Error('HTTP '+res.status);
  const data = await res.json();
  if (!data?.success) throw new Error(data?.error || 'Update failed');
}

function updateColumnStats(){
  document.querySelectorAll('.kanban-column-section').forEach(col=>{
    const count = col.querySelectorAll('.contract-card').length;
    const el = col.querySelector('.column-stats');
    if (el) el.textContent = count;
  });
}

function showNotification(message, type='success'){
  const n = document.createElement('div');
  n.className = 'notification' + (type==='error' ? ' error' : '');
  n.textContent = message;
  document.body.appendChild(n);
  setTimeout(()=>n.classList.add('show'), 50);
  setTimeout(()=>{
    n.classList.remove('show');
    setTimeout(()=> n.remove(), 300);
  }, 2500);
}
</script>
@endsection
