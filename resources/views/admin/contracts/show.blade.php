@extends('layouts.admin')

@section('title', 'Просмотр договора')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Договор №{{ $contract->contract_number }}</h1>
                            <p class="page-subtitle">Детальная информация о договоре</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button type="button" class="btn btn-danger"
                                onclick="showDeleteModal('{{ $contract->id }}', '{{ $contract->contract_number }}', 'contract')">
                            <i class="fas fa-trash"></i> Удалить
                        </button>
                        <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                        <a href="{{ route('contracts.export-word', $contract) }}" class="btn btn-secondary">
                            <i class="fas fa-file-word"></i> Экспорт в Word
                        </a>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Основная информация</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ $contract->category ?? '—' }}</div>
                                <div class="stat-label">Категория</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($contract->order_total) }} ₸</div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ $contract->date->format('d.m.Y') }}</div>
                                <div class="stat-label">Дата</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ $contract->user->name }}</div>
                                <div class="stat-label">Менеджер</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-cogs"></i>
                        <span>Действия с договором</span>
                    </div>
                    
                    <div class="personnel-section">
                        <div class="personnel-item" onclick="showHistoryModal('{{ $contract->id }}', '{{ $contract->contract_number }}')" style="cursor: pointer;">
                            <div class="personnel-icon">
                                <i class="fas fa-clock-rotate-left"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">История изменений</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">Просмотреть все изменения и одобрения по договору</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item" style="cursor: pointer;">
                            <div class="personnel-icon">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Запустить производство</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">Перевести договор в статус производства</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item" style="cursor: pointer;">
                            <div class="personnel-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Проверить качество</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">Отправить заказ на контроль качества</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-info-circle"></i>
                        <span>Основная информация</span>
                    </div>
                    <div class="personnel-section">
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Филиал</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->branch->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-cube"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Модель</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->model }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-ruler-combined"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Размеры</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->width }} × {{ $contract->height }} мм</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Способ оплаты</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->payment ?? 'Не указан' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Создан</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-sync"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Обновлён</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->updated_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-user"></i>
                        <span>Информация о клиенте</span>
                    </div>
                    
                    <div class="personnel-section">
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">ФИО</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->client }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">ИИН</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->iin }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Instagram</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->instagram }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Адрес</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->address ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Телефон</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->phone }}</span>
                                </div>
                            </div>
                        </div>
                        @if($contract->phone2)
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Доп. телефон</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->phone2 }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-cogs"></i>
                        <span>Технические характеристики</span>
                    </div>
                    
                    <div class="personnel-section">
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-th"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Внешняя панель</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->outer_panel }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-square"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Внешняя обшивка</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->outer_cover }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-square-full"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Внутренняя обшивка</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->inner_cover }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-paint-brush"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Внутренняя отделка</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->inner_trim }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-window-maximize"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Стеклопакет</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->glass_unit }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Замок</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->lock }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-hand-paper"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Ручка</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->handle }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-ruler"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Толщина стали</div>
                                <div class="personnel-list">
                                    <span class="personnel-tag">{{ $contract->steel_thickness }} мм</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($contract->extra)
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-plus-circle"></i>
                        <span>Дополнительно</span>
                    </div>
                    
                    <div class="personnel-section">
                        <div class="personnel-item">
                            <div class="personnel-content">
                                <div class="personnel-title">{{ $contract->extra }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-money-bill"></i>
                        <span>Финансовая информация</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($contract->order_total) }} ₸</div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($contract->order_deposit) }} ₸</div>
                                <div class="stat-label">Предоплата</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($contract->order_remainder) }} ₸</div>
                                <div class="stat-label">Остаток</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($contract->order_due) }} ₸</div>
                                <div class="stat-label">К оплате</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>

                @if($contract->photo_path || $contract->attachment_path)
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-paperclip"></i>
                        <span>Файлы</span>
                    </div>
                    
                    <div class="personnel-section">
                        @if($contract->photo_path)
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Фото двери</div>
                                <div class="personnel-list">
                                    <a href="{{ Storage::url($contract->photo_path) }}" target="_blank" class="personnel-tag">
                                        <i class="fas fa-external-link-alt tag-icon"></i> Открыть
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($contract->attachment_path)
                        <div class="personnel-item">
                            <div class="personnel-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="personnel-content">
                                <div class="personnel-title">Вложение</div>
                                <div class="personnel-list">
                                    <a href="{{ Storage::url($contract->attachment_path) }}" target="_blank" class="personnel-tag">
                                        <i class="fas fa-download tag-icon"></i> Скачать
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
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