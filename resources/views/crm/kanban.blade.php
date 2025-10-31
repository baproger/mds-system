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
                    <div class="contract-card" data-contract-id="{{ $contract->id }}" data-status="{{ $contract->status }}" draggable="true">
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

{{-- Styles moved to resources/css/app.css (Kanban unified) --}}

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  setupSortable();
  updateColumnStats();
  setupWheelHorizontalScroll();
  // Normalize progress UI on first render based on status mapping
  document.querySelectorAll('.contract-card').forEach(card => {
    const status = card.getAttribute('data-status');
    if (status) applyCardStatusUI(card, status);
  });
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
