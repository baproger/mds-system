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

                <!-- Поиск и фильтры - все на одной линии -->
                <div class="kanban-filters-section">
                    <div class="search-filters-line">
                        <!-- Поиск -->
                        <div class="search-box-modern">
                            <i class="fas fa-search search-icon-modern"></i>
                            <input type="text" id="kanbanSearch" class="search-input-modern" placeholder="Поиск по номеру договора, клиенту, телефону..." autocomplete="off">
                            <button type="button" class="clear-search-modern" id="clearSearch" style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Фильтры на одной линии -->
                        <div class="filters-line">
                            <div class="filter-item">
                                <select id="filterManager" class="filter-select-modern">
                                    <option value="">Менеджер</option>
                                    @foreach(\App\Models\User::where('role', 'manager')->get() as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <select id="filterPeriod" class="filter-select-modern">
                                    <option value="">Период</option>
                                    <option value="today">Сегодня</option>
                                    <option value="week">Эта неделя</option>
                                    <option value="month">Этот месяц</option>
                                    <option value="quarter">Этот квартал</option>
                                    <option value="year">Этот год</option>
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <select id="filterAmount" class="filter-select-modern">
                                    <option value="">Сумма</option>
                                    <option value="0-500000">До 500,000 ₸</option>
                                    <option value="500000-1000000">500,000 - 1,000,000 ₸</option>
                                    <option value="1000000-2000000">1,000,000 - 2,000,000 ₸</option>
                                    <option value="2000000">Более 2,000,000 ₸</option>
                                </select>
                            </div>
                            
                            <button type="button" id="toggleFilters" class="btn-filters-modern">
                                <i class="fas fa-sliders-h"></i>
                                <span>Фильтры</span>
                                <span class="filter-badge-modern" id="activeFiltersCount" style="display:none;">0</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Результаты (скрыто по умолчанию) -->
                    <div class="filters-results-modern" id="filterResults" style="display:none;">
                        <span class="results-text-modern">Найдено: <strong id="resultsCount">0</strong></span>
                        <button type="button" id="clearFilters" class="btn-clear-modern">
                            <i class="fas fa-times"></i> Сбросить
                        </button>
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
                    <div class="contract-card" 
                         data-contract-id="{{ $contract->id }}" 
                         data-status="{{ $contract->status }}"
                         data-manager-id="{{ $contract->user_id ?? '' }}"
                         data-branch-id="{{ $contract->branch_id ?? '' }}"
                         data-amount="{{ $contract->order_total ?? 0 }}"
                         data-date="{{ $contract->created_at->format('Y-m-d') }}"
                         data-contract-number="{{ $contract->contract_number ?? $contract->id }}"
                         data-client-name="{{ strtolower($contract->client ?? '') }}"
                         data-client-phone="{{ $contract->phone ?? '' }}"
                         draggable="true">
                      <div class="card-header">
                        <div class="card-number-wrapper">
                          <div class="status-dot {{ $contract->status }}"></div>
                        <div class="card-number">№{{ $contract->contract_number ?? $contract->id }}</div>
                        </div>
                        <div class="card-time">{{ $contract->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                        
                      <div class="card-progress">
                        <div class="progress">
                          <div class="progress-bar" style="width: {{ $contract->funnel_progress ?? 0 }}%"></div>
                        </div>
                        <span class="progress-text">{{ $contract->funnel_progress ?? 0 }}% выполнено</span>
                            </div>
                            
                      @can('deals.view-amounts')
                        <div class="card-amount-bottom">
                          {{ number_format($contract->order_total ?? 0, 0, ',', ' ') }} 〒
                                        </div>
                      @endcan

                      <div class="card-footer">
                        <div class="card-manager">
                          <i class="fa-solid fa-user manager-icon"></i>
                          <span class="manager-name">{{ $contract->user->name ?? 'Не назначен' }}</span>
                        </div>
                      <div class="card-actions">
                        <a href="{{ route(Auth::user()->role . '.contracts.show', $contract) }}" class="btn-action" title="Просмотр">
                          <i class="fa-regular fa-eye"></i>
                        </a>
                        </div>
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

/* ===== Фильтрация карточек ===== */
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('kanbanSearch');
  const clearSearchBtn = document.getElementById('clearSearch');
  const filterManager = document.getElementById('filterManager');
  const filterPeriod = document.getElementById('filterPeriod');
  const filterAmount = document.getElementById('filterAmount');
  const clearFiltersBtn = document.getElementById('clearFilters');
  const filterResults = document.getElementById('filterResults');
  const resultsCount = document.getElementById('resultsCount');
  const toggleFiltersBtn = document.getElementById('toggleFilters');
  const activeFiltersCount = document.getElementById('activeFiltersCount');
  
  // Подсчет активных фильтров
  function countActiveFilters() {
    let count = 0;
    if (filterManager?.value) count++;
    if (filterPeriod?.value) count++;
    if (filterAmount?.value) count++;
    
    if (activeFiltersCount) {
      if (count > 0) {
        activeFiltersCount.textContent = count;
        activeFiltersCount.style.display = 'inline-flex';
      } else {
        activeFiltersCount.style.display = 'none';
      }
    }
    
    return count;
  }
  
  // Обработчик очистки поиска
  if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', function() {
      searchInput.value = '';
      clearSearchBtn.classList.remove('visible');
      applyFilters();
    });
  }
  
  // Показывать кнопку очистки при вводе
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      if (this.value) {
        clearSearchBtn.classList.add('visible');
      } else {
        clearSearchBtn.classList.remove('visible');
      }
      applyFilters();
    });
  }
  
  // Обработчики фильтров
  [filterManager, filterPeriod, filterAmount].forEach(filter => {
    if (filter) {
      filter.addEventListener('change', function() {
        applyFilters();
        countActiveFilters();
      });
    }
  });
  
  // Кнопка переключения фильтров (для показа результатов)
  if (toggleFiltersBtn) {
    toggleFiltersBtn.addEventListener('click', function() {
      const hasActiveFilters = filterManager?.value || filterPeriod?.value || filterAmount?.value || searchInput?.value;
      if (hasActiveFilters) {
        filterResults.style.display = filterResults.style.display === 'none' ? 'flex' : 'none';
        toggleFiltersBtn.classList.toggle('active');
      }
    });
  }
  
  // Очистить все фильтры
  if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener('click', function() {
      searchInput.value = '';
      clearSearchBtn.classList.remove('visible');
      if (filterManager) filterManager.value = '';
      if (filterPeriod) filterPeriod.value = '';
      if (filterAmount) filterAmount.value = '';
      filterResults.style.display = 'none';
      if (toggleFiltersBtn) toggleFiltersBtn.classList.remove('active');
      applyFilters();
      countActiveFilters();
    });
  }
  
  // Функция применения фильтров
  function applyFilters() {
    const searchTerm = (searchInput?.value || '').toLowerCase().trim();
    const managerId = filterManager?.value || '';
    const period = filterPeriod?.value || '';
    const amountRange = filterAmount?.value || '';
    
    const allCards = document.querySelectorAll('.contract-card');
    let visibleCount = 0;
    
    allCards.forEach(card => {
      let matches = true;
      
      // Поиск по тексту (номер договора, клиент, телефон)
      if (searchTerm) {
        const contractNumber = (card.dataset.contractNumber || '').toLowerCase();
        const clientName = (card.dataset.clientName || '');
        const clientPhone = (card.dataset.clientPhone || '').replace(/\s+/g, '');
        const searchPhone = searchTerm.replace(/\s+/g, '');
        
        matches = matches && (
          contractNumber.includes(searchTerm) ||
          clientName.includes(searchTerm) ||
          clientPhone.includes(searchPhone)
        );
      }
      
      // Фильтр по менеджеру
      if (matches && managerId) {
        matches = matches && card.dataset.managerId === managerId;
      }
      
      // Фильтр по периоду
      if (matches && period) {
        const cardDate = new Date(card.dataset.date);
        const now = new Date();
        let startDate = new Date();
        
        switch(period) {
          case 'today':
            startDate.setHours(0, 0, 0, 0);
            break;
          case 'week':
            startDate.setDate(now.getDate() - now.getDay());
            startDate.setHours(0, 0, 0, 0);
            break;
          case 'month':
            startDate.setDate(1);
            startDate.setHours(0, 0, 0, 0);
            break;
          case 'quarter':
            const quarter = Math.floor(now.getMonth() / 3);
            startDate.setMonth(quarter * 3, 1);
            startDate.setHours(0, 0, 0, 0);
            break;
          case 'year':
            startDate.setMonth(0, 1);
            startDate.setHours(0, 0, 0, 0);
            break;
        }
        
        matches = matches && cardDate >= startDate && cardDate <= now;
      }
      
      // Фильтр по сумме
      if (matches && amountRange) {
        const amount = parseFloat(card.dataset.amount) || 0;
        const [min, max] = amountRange.split('-').map(v => v ? parseFloat(v) : null);
        
        if (max !== null) {
          matches = matches && amount >= min && amount < max;
        } else {
          matches = matches && amount >= min;
        }
      }
      
      // Показываем/скрываем карточку
      if (matches) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });
    
    // Обновляем счетчики колонок
    updateColumnStats();
    
    // Показываем результаты фильтрации если есть активные фильтры
    if (filterResults && resultsCount) {
      resultsCount.textContent = visibleCount;
      const hasActiveFilters = searchTerm || managerId || period || amountRange;
      if (hasActiveFilters) {
        filterResults.style.display = 'flex';
      } else {
        filterResults.style.display = 'none';
        if (toggleFiltersBtn) toggleFiltersBtn.classList.remove('active');
      }
    }
    
    // Обновляем счетчик активных фильтров
    countActiveFilters();
  }
  
  // Обновление счетчиков колонок с учетом фильтров
  function updateColumnStatsWithFilters() {
    document.querySelectorAll('.kanban-column-section').forEach(column => {
      const columnContent = column.querySelector('.column-content');
      const visibleCards = columnContent.querySelectorAll('.contract-card:not([style*="display: none"])');
      const stats = column.querySelector('.column-stats');
      if (stats) {
        stats.textContent = visibleCards.length;
      }
    });
  }
  
  // Переопределяем updateColumnStats для учета фильтров
  const originalUpdateColumnStats = window.updateColumnStats;
  window.updateColumnStats = function() {
    if (originalUpdateColumnStats) originalUpdateColumnStats();
    updateColumnStatsWithFilters();
  };
  
  // Инициализация при загрузке
  countActiveFilters();
});
</script>
@endsection
