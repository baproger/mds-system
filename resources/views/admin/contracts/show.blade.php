@extends('layouts.admin')

@section('title', 'Просмотр договора')

@section('content')
<div class="container-fluid">
    <!-- Заголовок -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Просмотр договора</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Дашборд</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contracts.index') }}">Договоры</a></li>
                    <li class="breadcrumb-item active">{{ $contract->contract_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
                         <button type="button" class="btn btn-admin btn-danger" onclick="showDeleteModal('{{ $contract->id }}', '{{ $contract->contract_number }}', 'contract')" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; color: white; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.background='linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.4)'" onmouseout="this.style.background='linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'">
                 <i class="fas fa-trash"></i> Удалить
             </button>
            <a href="{{ route('contracts.export-word', $contract) }}" class="btn btn-admin btn-success">
                <i class="fas fa-download"></i> Экспорт Word
            </a>
            <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-admin btn-primary">
                <i class="fas fa-edit"></i> Редактировать
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Основная информация -->
            <div class="table-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Основная информация</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Номер договора:</td>
                                    <td><span class="badge bg-primary">{{ $contract->contract_number }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Дата:</td>
                                    <td>{{ $contract->date->format('d.m.Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Филиал:</td>
                                    <td>{{ $contract->branch->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Менеджер:</td>
                                    <td>{{ $contract->user->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Категория:</td>
                                    <td><span class="badge bg-{{ $contract->category === 'Lux' ? 'danger' : ($contract->category === 'Premium' ? 'warning' : 'success') }}">{{ $contract->category }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Модель:</td>
                                    <td>{{ $contract->model }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Размеры:</td>
                                    <td>{{ $contract->width }}×{{ $contract->height }} мм</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Способ оплаты:</td>
                                    <td>{{ $contract->payment ?? 'Не указан' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Информация о клиенте -->
            <div class="table-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Информация о клиенте</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">ФИО:</td>
                                    <td>{{ $contract->client }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">ИИН:</td>
                                    <td>{{ $contract->iin }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Instagram:</td>
                                    <td>{{ $contract->instagram }}</td>
                                </tr>
                                @if($contract->address)
                                <tr>
                                    <td class="fw-bold">Адрес:</td>
                                    <td>{{ $contract->address }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Телефон:</td>
                                    <td>{{ $contract->phone }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Доп. телефон:</td>
                                    <td>{{ $contract->phone2 }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Технические характеристики -->
            <div class="table-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Технические характеристики</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Внешняя панель:</td>
                                    <td>{{ $contract->outer_panel }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Внешняя обшивка:</td>
                                    <td>{{ $contract->outer_cover }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Внутренняя обшивка:</td>
                                    <td>{{ $contract->inner_cover }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Внутренняя отделка:</td>
                                    <td>{{ $contract->inner_trim }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Стеклопакет:</td>
                                    <td>{{ $contract->glass_unit }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Замок:</td>
                                    <td>{{ $contract->lock }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ручка:</td>
                                    <td>{{ $contract->handle }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Толщина стали:</td>
                                    <td>{{ $contract->steel_thickness }} мм</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Финансовая информация -->
            <div class="table-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-money-bill"></i> Финансовая информация</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold">Общая сумма:</td>
                            <td class="text-end">{{ number_format($contract->order_total) }} ₸</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Предоплата:</td>
                            <td class="text-end">{{ number_format($contract->order_deposit) }} ₸</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Остаток:</td>
                            <td class="text-end">{{ number_format($contract->order_remainder) }} ₸</td>
                        </tr>
                        <tr class="table-primary">
                            <td class="fw-bold">К оплате:</td>
                            <td class="text-end fw-bold">{{ number_format($contract->order_due) }} ₸</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Дополнительная информация -->
            @if($contract->extra)
            <div class="table-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Дополнительно</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $contract->extra }}</p>
                </div>
            </div>
            @endif

            <!-- Файлы -->
            @if($contract->photo_path || $contract->attachment_path)
            <div class="table-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-paperclip"></i> Файлы</h5>
                </div>
                <div class="card-body">
                    @if($contract->photo_path)
                    <div class="mb-3">
                        <strong>Фото:</strong><br>
                        <a href="{{ Storage::url($contract->photo_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-image"></i> Просмотреть фото
                        </a>
                    </div>
                    @endif
                    @if($contract->attachment_path)
                    <div class="mb-3">
                        <strong>Вложение:</strong><br>
                        <a href="{{ Storage::url($contract->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-file"></i> Скачать вложение
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Действия -->
            <div class="table-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> Действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('contracts.export-word', $contract) }}" class="btn btn-admin btn-success">
                            <i class="fas fa-download"></i> Экспорт в Word
                        </a>
                        <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-admin btn-primary">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                                                 <button type="button" class="btn btn-admin btn-danger w-100" onclick="showDeleteModal('{{ $contract->id }}', '{{ $contract->contract_number }}', 'contract')" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; color: white; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.background='linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.4)'" onmouseout="this.style.background='linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'">
                             <i class="fas fa-trash"></i> Удалить
                         </button>
                    </div>
                </div>
            </div>
        </div>
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
                Вы действительно хотите удалить договор <strong id="deleteItemName"></strong>?
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
    
    // Создаем базовый URL для удаления
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

// Закрытие модального окна при клике вне его
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});
</script>

<style>
/* Модальное окно */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.modal-content {
    background: white;
    border-radius: 16px;
    padding: 32px;
    max-width: 450px;
    width: 90%;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(0, 0, 0, 0.1);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    text-align: center;
    margin-bottom: 28px;
    display: inline !important;
}

.modal-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2);
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 12px;
    line-height: 1.3;
}

.modal-subtitle {
    color: #6b7280;
    font-size: 15px;
    line-height: 1.6;
    margin: 0;
}

.modal-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-top: 32px;
}

.modal-btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    min-width: 120px;
    justify-content: center;
}

.modal-btn-cancel {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
}

.modal-btn-cancel:hover {
    background: #e5e7eb;
    color: #111827;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.modal-btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.modal-btn-delete:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

/* Адаптивность */
@media (max-width: 480px) {
    .modal-content {
        padding: 24px;
        margin: 20px;
    }
    
    .modal-actions {
        flex-direction: column;
        gap: 12px;
    }
    
    .modal-btn {
        width: 100%;
    }
}
</style>

@endsection 