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
            <a href="{{ route('contracts.print', $contract) }}" class="btn btn-admin btn-info" target="_blank">
                <i class="fas fa-print"></i> Печать
            </a>
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
                        <a href="{{ route('contracts.print', $contract) }}" class="btn btn-admin btn-info" target="_blank">
                            <i class="fas fa-print"></i> Печать договора
                        </a>
                        <a href="{{ route('contracts.export-word', $contract) }}" class="btn btn-admin btn-success">
                            <i class="fas fa-download"></i> Экспорт в Word
                        </a>
                        <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-admin btn-primary">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                        <form action="{{ route('admin.contracts.delete', $contract) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Вы уверены, что хотите удалить этот договор?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-admin btn-danger w-100">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 