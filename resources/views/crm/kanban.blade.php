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
                        <button type="button" class="btn-action btn-history" data-contract-id="{{ $contract->id }}" data-contract-number="{{ $contract->contract_number ?? $contract->id }}" title="История статусов">
                          <i class="fa-solid fa-clock-rotate-left"></i>
                        </button>
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

// Проверка допустимости перехода между статусами
// Предотвращает перемещение заявок сразу в конец воронки без прохождения промежуточных этапов
function isValidStatusTransition(oldStatus, newStatus) {
  // Порядок статусов в воронке (последовательность этапов)
  const funnelOrder = [
    'draft',
    'pending_rop',
    'approved',
    'ready',
    'shipped',
    'completed'
  ];

  // Специальные статусы (не в основной воронке, но могут использоваться)
  const specialStatuses = ['rejected', 'returned'];

  // Если перемещение из завершенного в доработку - разрешаем
  if (oldStatus === 'completed' && newStatus === 'returned') {
    return true;
  }

  // Завершенные нельзя перемещать в другие статусы
  if (oldStatus === 'completed' && newStatus !== 'returned') {
    return false;
  }

  // Если перемещение в специальный статус, разрешаем (они обрабатываются отдельно на сервере)
  if (specialStatuses.includes(newStatus)) {
    return true;
  }

  // Если перемещение из специального статуса в обычный, разрешаем (проверка будет на сервере)
  if (specialStatuses.includes(oldStatus)) {
    return true;
  }

  // Получаем индексы текущего и нового статуса в воронке
  const oldIndex = funnelOrder.indexOf(oldStatus);
  const newIndex = funnelOrder.indexOf(newStatus);

  // Если один из статусов не найден в воронке, разрешаем (проверка будет на сервере)
  if (oldIndex === -1 || newIndex === -1) {
    return true;
  }

  // Вычисляем разницу шагов
  const stepDiff = newIndex - oldIndex;
  
  // Разрешаем:
  // - Переход назад (stepDiff < 0) - можно вернуться на любой предыдущий этап
  // - Остаться на месте (stepDiff === 0) - в ту же колонку (будет отфильтровано в onEnd)
  // - Один шаг вперед (stepDiff === 1) - следующий этап
  // Запрещаем:
  // - Пропуск этапов вперед (stepDiff > 1) - нельзя перепрыгивать через этапы
  return stepDiff <= 1;
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
      onMove: function(evt) {
        // Проверяем допустимость перехода перед перемещением
        const oldStatus = evt.from.closest('.kanban-column-section')?.dataset.status;
        const newStatus = evt.to.closest('.kanban-column-section')?.dataset.status;
        
        if (!oldStatus || !newStatus) {
          return true; // Разрешаем, если не можем определить статусы
        }

        // Если переход недопустим, блокируем перемещение
        if (!isValidStatusTransition(oldStatus, newStatus)) {
          showNotification('Недопустимый переход! Заявку нельзя перемещать в эту стадию без прохождения промежуточных этапов.', 'error');
          return false; // Блокируем перемещение
        }

        return true; // Разрешаем перемещение
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

        // Дополнительная проверка на всякий случай
        if (!isValidStatusTransition(oldStatus, newStatus)) {
          // Возвращаем карточку в исходную колонку
          const oldColumn = document.querySelector(`[data-status="${oldStatus}"] .column-content`);
          if (oldColumn) {
            oldColumn.appendChild(item);
          }
          updateColumnStats();
          showNotification('Недопустимый переход! Заявку нельзя перемещать в эту стадию без прохождения промежуточных этапов.', 'error');
          return;
        }

        // Показываем модальное окно для комментария
        showCommentModal(contractId, newStatus, item, oldStatus);
      }
    });
});
}

// Сохраняем ссылку на карточку для возврата
let currentDragItem = null;
let currentDragOldStatus = null;

/* Показать модальное окно для комментария */
function showCommentModal(contractId, newStatus, item, oldStatus) {
  const modal = document.getElementById('commentModal');
  const commentInput = document.getElementById('commentInput');
  const statusLabel = modal.querySelector('.comment-modal-status-label');
  
  // Сохраняем ссылку на карточку
  currentDragItem = item;
  currentDragOldStatus = oldStatus;
  
  // Получаем названия статусов
  const statusLabels = {
    'draft': 'Новая заявка',
    'pending_rop': 'На рассмотрении',
    'approved': 'Одобрено',
    'rejected': 'Отклонено',
    'ready': 'Готово',
    'shipped': 'Отправлено',
    'completed': 'Завершено',
    'returned': 'На доработке'
  };
  
  statusLabel.textContent = statusLabels[newStatus] || newStatus;
  commentInput.value = '';
  modal.setAttribute('data-contract-id', contractId);
  modal.setAttribute('data-new-status', newStatus);
  modal.setAttribute('data-old-status', oldStatus);
  modal.classList.add('show');
  commentInput.focus();
}

/* Обновление статуса контракта */
async function updateContractStatus(contractId, newStatus, item, oldStatus, comment = null) {
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
      body: JSON.stringify({ status: newStatus, comment: comment })
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }

    const data = await response.json();
    
    if (data.success) {
      // Обновляем UI карточки
      applyCardStatusUI(item, newStatus);
      updateColumnStats();
      showNotification('Заявка №' + contractId + ' перемещена в "' + statusLabel(newStatus) + '"', 'success');
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
    ready: 70,
    shipped: 85,
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
  
  // Подсчет активных фильтров
  function countActiveFilters() {
    let count = 0;
    if (filterManager?.value) count++;
    if (filterPeriod?.value) count++;
    if (filterAmount?.value) count++;
    
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
  
  // Очистить все фильтры
  if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener('click', function() {
      searchInput.value = '';
      clearSearchBtn.classList.remove('visible');
      if (filterManager) filterManager.value = '';
      if (filterPeriod) filterPeriod.value = '';
      if (filterAmount) filterAmount.value = '';
      filterResults.style.display = 'none';
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

/* ===== История статусов ===== */
document.addEventListener('DOMContentLoaded', function() {
  // Обработчик для кнопок истории
  document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-history')) {
      const button = e.target.closest('.btn-history');
      const contractId = button.getAttribute('data-contract-id');
      const contractNumber = button.getAttribute('data-contract-number');
      if (contractId) {
        showHistoryModal(contractId, contractNumber);
      }
    }
  });

  // Закрытие модального окна
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('history-modal-overlay') || e.target.closest('.history-modal-close')) {
      closeHistoryModal();
    }
  });

  // Закрытие по ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.querySelector('.history-modal-overlay.show')) {
      closeHistoryModal();
    }
    if (e.key === 'Escape' && document.querySelector('.comment-modal-overlay.show')) {
      closeCommentModal();
    }
  });

  // Обработчик для модального окна комментария
  const commentConfirmBtn = document.getElementById('commentConfirmBtn');
  const commentCancelBtn = document.getElementById('commentCancelBtn');
  const commentInput = document.getElementById('commentInput');
  
  if (commentConfirmBtn) {
    commentConfirmBtn.addEventListener('click', function() {
      const modal = document.getElementById('commentModal');
      const contractId = modal.getAttribute('data-contract-id');
      const newStatus = modal.getAttribute('data-new-status');
      const oldStatus = modal.getAttribute('data-old-status');
      const comment = commentInput.value.trim();
      
      // Находим карточку контракта
      const card = document.querySelector(`[data-contract-id="${contractId}"]`);
      const oldColumn = document.querySelector(`[data-status="${oldStatus}"] .column-content`);
      
      if (!card || !oldColumn) {
        closeCommentModal();
        return;
      }
      
      // Закрываем модальное окно
      closeCommentModal();
      
      // Обновляем статус с комментарием
      updateContractStatus(contractId, newStatus, card, oldStatus, comment);
    });
  }
  
  if (commentCancelBtn) {
    commentCancelBtn.addEventListener('click', function() {
      // Возвращаем карточку в исходную колонку
      if (currentDragItem && currentDragOldStatus) {
        const oldColumn = document.querySelector(`[data-status="${currentDragOldStatus}"] .column-content`);
        if (oldColumn && currentDragItem.parentElement !== oldColumn) {
          oldColumn.appendChild(currentDragItem);
          updateColumnStats();
        }
      }
      
      closeCommentModal();
    });
  }
  
  // Закрытие по клику на overlay
  const commentModal = document.getElementById('commentModal');
  if (commentModal) {
    commentModal.addEventListener('click', function(e) {
      if (e.target === commentModal) {
        // Возвращаем карточку при закрытии
        if (currentDragItem && currentDragOldStatus) {
          const oldColumn = document.querySelector(`[data-status="${currentDragOldStatus}"] .column-content`);
          if (oldColumn && currentDragItem.parentElement !== oldColumn) {
            oldColumn.appendChild(currentDragItem);
            updateColumnStats();
          }
        }
        closeCommentModal();
      }
    });
  }
  
  // Подтверждение комментария по Enter (Ctrl+Enter)
  if (commentInput) {
    commentInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
        commentConfirmBtn.click();
      }
    });
  }
});

function closeCommentModal() {
  const modal = document.getElementById('commentModal');
  modal.classList.remove('show');
  const commentInput = document.getElementById('commentInput');
  if (commentInput) {
    commentInput.value = '';
  }
  // Очищаем ссылки
  currentDragItem = null;
  currentDragOldStatus = null;
}

async function showHistoryModal(contractId, contractNumber = null) {
  const overlay = document.getElementById('historyModal');
  const content = overlay.querySelector('.history-modal-content');
  const loader = overlay.querySelector('.history-loader');
  const list = overlay.querySelector('.history-list');
  const titleElement = overlay.querySelector('.history-modal-title span');
  
  // Показываем модальное окно
  overlay.classList.add('show');
  list.innerHTML = '';
  loader.style.display = 'flex';
  
  // Устанавливаем временный номер заявки если он был передан
  if (contractNumber) {
    titleElement.textContent = `История статусов заявки №${contractNumber}`;
  }

  try {
    const response = await fetch(`{{ route(Auth::user()->role . '.crm.history', ['contract' => 'CONTRACT_ID']) }}`.replace('CONTRACT_ID', contractId), {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });

    if (!response.ok) {
      throw new Error('Ошибка загрузки истории');
    }

    const data = await response.json();
    
    // Обновляем заголовок с номером заявки из ответа
    if (data.contract_number) {
      const titleElement = overlay.querySelector('.history-modal-title span');
      titleElement.textContent = `История статусов заявки №${data.contract_number}`;
    }
    
    if (data.success && data.history.length > 0) {
      list.innerHTML = data.history.map((item, index) => {
        const isLast = index === 0;
        const statusColors = {
          'draft': '#6b7280',
          'pending_rop': '#f59e0b',
          'approved': '#10b981',
          'rejected': '#ef4444',
          'ready': '#84cc16',
          'shipped': '#f97316',
          'completed': '#059669',
          'returned': '#6b7280'
        };
        
        const oldColor = statusColors[item.old_status] || '#9ca3af';
        const newColor = statusColors[item.new_status] || '#3b82f6';
        
        return `
          <div class="history-item ${isLast ? 'history-item-latest' : ''}">
            <div class="history-timeline">
              <div class="history-dot" style="background: ${newColor}; border-color: ${newColor};"></div>
              ${!isLast ? '<div class="history-line"></div>' : ''}
            </div>
            <div class="history-content">
              <div class="history-status-change">
                <span class="status-badge status-badge-old" style="background: ${oldColor}15; color: ${oldColor}; border-color: ${oldColor}40;">
                  ${item.old_status_label}
                </span>
                <i class="fa-solid fa-arrow-right history-arrow"></i>
                <span class="status-badge status-badge-new" style="background: ${newColor}15; color: ${newColor}; border-color: ${newColor}40;">
                  ${item.new_status_label}
                </span>
              </div>
              <div class="history-meta">
                <div class="history-user">
                  <i class="fa-solid fa-user"></i>
                  <span>${item.user_name}</span>
                  <span class="history-role">(${getRoleLabel(item.user_role)})</span>
                </div>
                <div class="history-time">
                  <i class="fa-solid fa-clock"></i>
                  <span>${item.changed_at}</span>
                </div>
              </div>
              ${item.comment ? `
              <div class="history-comment">
                <i class="fa-solid fa-comment"></i>
                <span>${item.comment}</span>
              </div>
              ` : ''}
            </div>
          </div>
        `;
      }).join('');
    } else {
      list.innerHTML = '<div class="history-empty">История изменений статусов отсутствует</div>';
    }
  } catch (error) {
    console.error('Ошибка загрузки истории:', error);
    list.innerHTML = '<div class="history-error">Ошибка загрузки истории. Попробуйте позже.</div>';
  } finally {
    loader.style.display = 'none';
  }
}

function closeHistoryModal() {
  const overlay = document.getElementById('historyModal');
  overlay.classList.remove('show');
}

function getRoleLabel(role) {
  const labels = {
    'admin': 'Администратор',
    'manager': 'Менеджер',
    'rop': 'РОП',
    'production': 'Производство',
    'accountant': 'Бухгалтер'
  };
  return labels[role] || role;
}
</script>

<!-- Модальное окно истории -->
<div class="history-modal-overlay" id="historyModal">
  <div class="history-modal-content">
    <div class="history-modal-header">
      <div class="history-modal-title">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span>История статусов заявки</span>
      </div>
      <button type="button" class="history-modal-close">
        <i class="fa-solid fa-times"></i>
      </button>
    </div>
    <div class="history-modal-body">
      <div class="history-loader">
        <div class="history-spinner"></div>
        <span>Загрузка истории...</span>
      </div>
      <div class="history-list"></div>
    </div>
  </div>
</div>

<style>
/* Модальное окно истории */
.history-modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  z-index: 10000;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.history-modal-overlay.show {
  display: flex;
  opacity: 1;
}

.history-modal-content {
  background: #ffffff;
  border-radius: 20px;
  width: 90%;
  max-width: 700px;
  max-height: 85vh;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
  display: flex;
  flex-direction: column;
  animation: historyModalSlideIn 0.3s ease-out;
}

@keyframes historyModalSlideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.history-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 24px 28px;
  border-bottom: 1px solid #e5e7eb;
  background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
  border-radius: 20px 20px 0 0;
}

.history-modal-title {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 20px;
  font-weight: 700;
  color: #1f2937;
}

.history-modal-title i {
  color: #3b82f6;
  font-size: 22px;
}

.history-modal-close {
  background: transparent;
  border: none;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s ease;
}

.history-modal-close:hover {
  background: #f3f4f6;
  color: #374151;
  transform: rotate(90deg);
}

.history-modal-body {
  padding: 24px 28px;
  overflow-y: auto;
  flex: 1;
}

.history-loader {
  display: none;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  gap: 16px;
}

.history-loader.show {
  display: flex;
}

.history-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e5e7eb;
  border-top-color: #3b82f6;
  border-radius: 50%;
  animation: historySpinnerRotate 0.8s linear infinite;
}

@keyframes historySpinnerRotate {
  to { transform: rotate(360deg); }
}

.history-loader span {
  color: #6b7280;
  font-size: 14px;
  font-weight: 500;
}

.history-list {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.history-item {
  display: flex;
  gap: 20px;
  padding: 20px 0;
  position: relative;
}

.history-item-latest {
  background: linear-gradient(90deg, #eff6ff 0%, transparent 100%);
  border-radius: 12px;
  padding: 20px 16px;
  margin: 0 -16px;
}

.history-timeline {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
}

.history-dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  border: 3px solid;
  background: #3b82f6;
  z-index: 2;
  position: relative;
}

.history-item-latest .history-dot {
  width: 18px;
  height: 18px;
  border-width: 4px;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.history-line {
  width: 2px;
  flex: 1;
  background: linear-gradient(180deg, #e5e7eb 0%, transparent 100%);
  min-height: 40px;
  margin-top: 8px;
}

.history-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.history-status-change {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.status-badge {
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  border: 1px solid;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.status-badge-old {
  opacity: 0.7;
}

.history-arrow {
  color: #9ca3af;
  font-size: 12px;
  flex-shrink: 0;
}

.history-meta {
  display: flex;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
  font-size: 12px;
  color: #6b7280;
}

.history-user,
.history-time {
  display: flex;
  align-items: center;
  gap: 6px;
}

.history-user i,
.history-time i {
  font-size: 11px;
  color: #9ca3af;
}

.history-role {
  color: #9ca3af;
  font-size: 11px;
  font-weight: 400;
}

.history-empty,
.history-error {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
  font-size: 14px;
}

.history-error {
  color: #ef4444;
}

/* Скроллбар для модального окна */
.history-modal-body::-webkit-scrollbar {
  width: 6px;
}

.history-modal-body::-webkit-scrollbar-track {
  background: #f3f4f6;
  border-radius: 3px;
}

.history-modal-body::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
}

.history-modal-body::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

.history-comment {
  margin-top: 12px;
  padding: 10px 14px;
  background: #f9fafb;
  border-left: 3px solid #3b82f6;
  border-radius: 8px;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 13px;
  line-height: 1.5;
  color: #374151;
}

.history-comment i {
  color: #3b82f6;
  margin-top: 2px;
  flex-shrink: 0;
  font-size: 14px;
}

.history-comment span {
  flex: 1;
  white-space: pre-wrap;
  word-wrap: break-word;
}

/* Модальное окно для комментария */
.comment-modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  z-index: 10001;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.comment-modal-overlay.show {
  display: flex;
  opacity: 1;
}

.comment-modal-content {
  background: #ffffff;
  border-radius: 20px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
  animation: commentModalSlideIn 0.3s ease-out;
}

@keyframes commentModalSlideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.comment-modal-header {
  padding: 24px 28px;
  border-bottom: 1px solid #e5e7eb;
  background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
  border-radius: 20px 20px 0 0;
}

.comment-modal-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 8px;
}

.comment-modal-subtitle {
  font-size: 14px;
  color: #6b7280;
}

.comment-modal-status-label {
  display: inline-block;
  padding: 4px 12px;
  background: #eff6ff;
  color: #3b82f6;
  border-radius: 6px;
  font-weight: 600;
  font-size: 13px;
  margin-top: 8px;
}

.comment-modal-body {
  padding: 24px 28px;
}

.comment-input-wrapper {
  position: relative;
}

.comment-input-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.comment-input {
  width: 100%;
  min-height: 100px;
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 14px;
  font-family: Inter, system-ui, sans-serif;
  color: #1f2937;
  resize: vertical;
  transition: all 0.2s ease;
  outline: none;
}

.comment-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.comment-input::placeholder {
  color: #9ca3af;
}

.comment-input-hint {
  margin-top: 8px;
  font-size: 12px;
  color: #6b7280;
  display: flex;
  align-items: center;
  gap: 6px;
}

.comment-modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 20px;
}

.comment-btn {
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 14px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.comment-btn-cancel {
  background: #f3f4f6;
  color: #374151;
}

.comment-btn-cancel:hover {
  background: #e5e7eb;
  transform: translateY(-1px);
}

.comment-btn-confirm {
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: #ffffff;
  box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

.comment-btn-confirm:hover {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.comment-btn:active {
  transform: translateY(0);
}
</style>

<!-- Модальное окно для комментария -->
<div class="comment-modal-overlay" id="commentModal">
  <div class="comment-modal-content">
    <div class="comment-modal-header">
      <div class="comment-modal-title">
        <i class="fa-solid fa-comment-dots"></i> Комментарий к изменению статуса
      </div>
      <div class="comment-modal-subtitle">
        Перемещение в статус: <span class="comment-modal-status-label"></span>
      </div>
    </div>
    <div class="comment-modal-body">
      <div class="comment-input-wrapper">
        <label class="comment-input-label">Комментарий (необязательно)</label>
        <textarea 
          id="commentInput" 
          class="comment-input" 
          placeholder="Добавьте комментарий к изменению статуса..."
          maxlength="500"></textarea>
        <div class="comment-input-hint">
          <i class="fa-solid fa-info-circle"></i>
          <span>Нажмите Ctrl+Enter или кнопку "Подтвердить" для сохранения</span>
        </div>
      </div>
      <div class="comment-modal-actions">
        <button type="button" class="comment-btn comment-btn-cancel" id="commentCancelBtn">
          <i class="fa-solid fa-times"></i>
          Отмена
        </button>
        <button type="button" class="comment-btn comment-btn-confirm" id="commentConfirmBtn">
          <i class="fa-solid fa-check"></i>
          Подтвердить
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
