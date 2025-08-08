@extends('layouts.app')

@section('title', 'Редактировать договор')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-edit"></i> Редактировать договор</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.update' : (Auth::user()->role === 'manager' ? 'manager.contracts.update' : 'rop.contracts.update'), $contract) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Основная информация -->
                    <div class="form-section">
                        <h5><i class="fas fa-info-circle"></i> Основная информация</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label required">Дата договора</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                           id="date" name="date" value="{{ old('date', $contract->date->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contract_number" class="form-label required">Номер договора</label>
                                    <input type="text" class="form-control @error('contract_number') is-invalid @enderror" 
                                           id="contract_number" name="contract_number" value="{{ old('contract_number', $contract->contract_number) }}" required>
                                    @php
                                        $ranges = [
                                            'SHY-PP' => '20000-29999',
                                            'SHY-RZ' => '30000-39999',
                                            'AKT' => '40000-49999',
                                            'ALA-TST' => '50000-57999',
                                            'ALA-SC' => '58000-59999',
                                            'TRZ' => '100000-119999',
                                            'ATR' => '120000-139999',
                                            'TAS' => '60000-69999',
                                        ];
                                        $range = $ranges[$contract->branch->code] ?? 'неизвестен';
                                    @endphp
                                    <small class="form-text text-muted">Введите номер договора вручную. Диапазон для филиала "{{ $contract->branch->name }}": {{ $range }}</small>
                                    @error('contract_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manager" class="form-label required">Менеджер</label>
                                    <select class="form-control @error('manager') is-invalid @enderror" 
                                            id="manager" name="manager" required>
                                        <option value="">— выберите —</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager }}" {{ old('manager', $contract->manager) == $manager ? 'selected' : '' }}>
                                                {{ $manager }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('manager')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment" class="form-label">Способ оплаты</label>
                                    <select class="form-control @error('payment') is-invalid @enderror" 
                                            id="payment" name="payment">
                                        <option value="Наличный" {{ old('payment', $contract->payment) == 'Наличный' ? 'selected' : '' }}>Наличный</option>
                                        <option value="Безналичный" {{ old('payment', $contract->payment) == 'Безналичный' ? 'selected' : '' }}>Безналичный</option>
                                        <option value="Рассрочка" {{ old('payment', $contract->payment) == 'Рассрочка' ? 'selected' : '' }}>Рассрочка</option>
                                    </select>
                                    @error('payment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о клиенте -->
                    <div class="form-section">
                        <h5><i class="fas fa-user"></i> Информация о клиенте</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client" class="form-label required">ФИО клиента</label>
                                    <input type="text" class="form-control @error('client') is-invalid @enderror" 
                                           id="client" name="client" value="{{ old('client', $contract->client) }}" required>
                                    @error('client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="iin" class="form-label required">ИИН клиента</label>
                                    <input type="text" class="form-control @error('iin') is-invalid @enderror" 
                                           id="iin" name="iin" value="{{ old('iin', $contract->iin) }}" maxlength="12" required>
                                    @error('iin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label required">Телефон</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $contract->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone2" class="form-label">Доп. телефон</label>
                                    <input type="text" class="form-control @error('phone2') is-invalid @enderror" 
                                           id="phone2" name="phone2" value="{{ old('phone2', $contract->phone2) }}">
                                    @error('phone2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                           id="instagram" name="instagram" value="{{ old('instagram', $contract->instagram) }}">
                                    @error('instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label required">Адрес установки</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="2" required>{{ old('address', $contract->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Характеристики двери -->
                    <div class="form-section">
                        <h5><i class="fas fa-door-open"></i> Характеристики двери</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label required">Категория</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">— выберите —</option>
                                        <option value="Lux" {{ old('category', $contract->category) == 'Lux' ? 'selected' : '' }}>Lux</option>
                                        <option value="Premium" {{ old('category', $contract->category) == 'Premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="Comfort" {{ old('category', $contract->category) == 'Comfort' ? 'selected' : '' }}>Comfort</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="model" class="form-label required">Модель</label>
                                    <select class="form-control @error('model') is-invalid @enderror" 
                                            id="model" name="model" required>
                                        <option value="">Сначала выберите категорию</option>
                                        @if(isset($price[$contract->category]))
                                            @foreach(array_keys($price[$contract->category]) as $m)
                                                <option value="{{ $m }}" {{ old('model', $contract->model) == $m ? 'selected' : '' }}>{{ $m }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="width" class="form-label required">Ширина (мм)</label>
                                    <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                           id="width" name="width" value="{{ old('width', $contract->width) }}" min="850" step="0.01" required>
                                    @error('width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="height" class="form-label required">Высота (мм)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                           id="height" name="height" value="{{ old('height', $contract->height) }}" min="850" step="0.01" required>
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="leaf" class="form-label required">Створка</label>
                                    <select class="form-control @error('leaf') is-invalid @enderror" id="leaf" name="leaf" required>
                                        <option value="">— выберите —</option>
                                        <option value="1" {{ old('leaf', $contract->leaf) == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('leaf', $contract->leaf) == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('leaf', $contract->leaf) == '3' ? 'selected' : '' }}>3</option>
                                    </select>
                                    @error('leaf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="framugawidth" class="form-label required">Фрамуга боковая</label>
                                    <select class="form-control @error('framugawidth') is-invalid @enderror" id="framugawidth" name="framugawidth" required>
                                        <option value="">— выберите —</option>
                                        <option value="1" {{ old('framugawidth', $contract->framugawidth) == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('framugawidth', $contract->framugawidth) == '2' ? 'selected' : '' }}>2</option>
                                        <option value="-" {{ old('framugawidth', $contract->framugawidth) == '-' ? 'selected' : '' }}>–</option>
                                    </select>
                                    @error('framugawidth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="framugaheight" class="form-label required">Фрамуга верхняя</label>
                                    <select class="form-control @error('framugaheight') is-invalid @enderror" id="framugaheight" name="framugaheight" required>
                                        <option value="">— выберите —</option>
                                        <option value="1" {{ old('framugaheight', $contract->framugaheight) == '1' ? 'selected' : '' }}>1</option>
                                        <option value="-" {{ old('framugaheight', $contract->framugaheight) == '-' ? 'selected' : '' }}>–</option>
                                    </select>
                                    @error('framugaheight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="steel_thickness" class="form-label">Толщина стали (мм)</label>
                                    <input type="number" class="form-control @error('steel_thickness') is-invalid @enderror" 
                                           id="steel_thickness" name="steel_thickness" value="{{ old('steel_thickness', $contract->steel_thickness) }}" step="0.01">
                                    @error('steel_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="canvas_thickness" class="form-label">Толщина полотна (мм)</label>
                                    <input type="number" class="form-control @error('canvas_thickness') is-invalid @enderror" 
                                           id="canvas_thickness" name="canvas_thickness" value="{{ old('canvas_thickness', $contract->canvas_thickness) }}" step="0.01">
                                    @error('canvas_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="installation" class="form-label">Установка</label>
                                    <input type="number" class="form-control @error('installation') is-invalid @enderror" 
                                           id="installation" name="installation" value="{{ old('installation', $contract->installation) }}">
                                    @error('installation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="delivery" class="form-label">Доставка</label>
                                    <input type="text" class="form-control @error('delivery') is-invalid @enderror" 
                                           id="delivery" name="delivery" value="{{ old('delivery', $contract->delivery) }}">
                                    @error('delivery')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Покрытия и материалы -->
                    <div class="form-section">
                        <h5><i class="fas fa-palette"></i> Покрытия и материалы</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metal_cover" class="form-label">Покрытие металла</label>
                                    <input type="text" class="form-control @error('metal_cover') is-invalid @enderror" 
                                           id="metal_cover" name="metal_cover" value="{{ old('metal_cover', $contract->metal_cover) }}">
                                    @error('metal_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metal_cover_color" class="form-label">Цвет покрытия металла</label>
                                    <input type="text" class="form-control @error('metal_cover_color') is-invalid @enderror" 
                                           id="metal_cover_color" name="metal_cover_color" value="{{ old('metal_cover_color', $contract->metal_cover_color) }}">
                                    @error('metal_cover_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="outer_cover" class="form-label required">Покрытие наружной панели</label>
                                    <select class="form-control @error('outer_cover') is-invalid @enderror" 
                                            id="outer_cover" name="outer_cover" required>
                                        <option value="">Сначала выберите категорию и модель</option>
                                    </select>
                                    @error('outer_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="outer_cover_color" class="form-label">Цвет покрытия наружной панели</label>
                                    <input type="text" class="form-control" 
                                           id="outer_cover_color" name="outer_cover_color" 
                                           placeholder="Пример: RAL 9010" value="{{ old('outer_cover_color', $contract->outer_cover_color) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inner_trim" class="form-label required">Внутренняя обшивка</label>
                                    <select class="form-control @error('inner_trim') is-invalid @enderror" 
                                            id="inner_trim" name="inner_trim" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('inner_trim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inner_cover" class="form-label required">Покрытие внутренней обшивки</label>
                                    <select class="form-control @error('inner_cover') is-invalid @enderror" 
                                            id="inner_cover" name="inner_cover" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('inner_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="glass_unit" class="form-label required">Стеклопакет</label>
                                    <select class="form-control @error('glass_unit') is-invalid @enderror" 
                                            id="glass_unit" name="glass_unit" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('glass_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lock" class="form-label required">Замок</label>
                                    <select class="form-control @error('lock') is-invalid @enderror" 
                                            id="lock" name="lock" required>
                                        <option value="">— выберите —</option>
                                    </select>
                                    @error('lock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="handle" class="form-label required">Ручка</label>
                                    <input type="text" class="form-control @error('handle') is-invalid @enderror" 
                                           id="handle" name="handle" value="{{ old('handle', $contract->handle) }}" required>
                                    @error('handle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительные услуги -->
                    <div class="form-section">
                        <h5><i class="fas fa-tools"></i> Дополнительные услуги</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="measurement" class="form-label">Замер</label>
                                    <input type="text" class="form-control @error('measurement') is-invalid @enderror" 
                                           id="measurement" name="measurement" value="{{ old('measurement', $contract->measurement) }}">
                                    @error('measurement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="extra" class="form-label">Дополнительные услуги</label>
                                    <input type="text" class="form-control @error('extra') is-invalid @enderror" 
                                           id="extra" name="extra" value="{{ old('extra', $contract->extra) }}">
                                    @error('extra')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Финансовая информация -->
                    <div class="form-section">
                        <h5><i class="fas fa-money-bill-wave"></i> Финансовая информация</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_total" class="form-label required">Общая стоимость</label>
                                    <input type="number" class="form-control @error('order_total') is-invalid @enderror" 
                                           id="order_total" name="order_total" value="{{ old('order_total', $contract->order_total) }}" min="0" step="1" required>
                                    @error('order_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_deposit" class="form-label required">Предоплата</label>
                                    <input type="number" class="form-control @error('order_deposit') is-invalid @enderror" 
                                           id="order_deposit" name="order_deposit" value="{{ old('order_deposit', $contract->order_deposit) }}" min="0" step="1" required>
                                    @error('order_deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_remainder" class="form-label required">Остаток предоплаты</label>
                                    <input type="number" class="form-control @error('order_remainder') is-invalid @enderror" 
                                           id="order_remainder" name="order_remainder" value="{{ old('order_remainder', $contract->order_remainder) }}" min="0" step="1" required>
                                    @error('order_remainder')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_due" class="form-label required">К оплате после изготовления</label>
                                    <input type="number" class="form-control @error('order_due') is-invalid @enderror" 
                                           id="order_due" name="order_due" value="{{ old('order_due', $contract->order_due) }}" min="0" step="1" required>
                                    @error('order_due')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки действий -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Назад
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const price = @json($price);
const fusionModels = @json($fusionModels);

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const modelSelect = document.getElementById('model');
    const outerCoverSelect = document.getElementById('outer_cover');
    const innerTrimSelect = document.getElementById('inner_trim');
    const innerCoverSelect = document.getElementById('inner_cover');
    const glassUnitSelect = document.getElementById('glass_unit');
    const lockSelect = document.getElementById('lock');
    const extraInput = document.getElementById('extra');

    const config = {
        outer_panel: { Lux: "Оцинкованная сталь", Premium: "Оцинкованная сталь", Comfort: "Холоднокатанная сталь" },
        fusion_outer_cover: ["МДФ-эмаль", "Шпон-марилька"],
        default_outer_cover: {
            Lux: ["Оцинкованная сталь"],
            Premium: ["Оцинкованная сталь"],
            Comfort: ["Холоднокатанная сталь"]
        },
        inner_trim: {
            Lux: ["МДФ", "металл", "шпон"],
            Premium: ["МДФ", "металл", "шпон (за доплату)"],
            Comfort: ["МДФ", "металл"]
        },
        inner_cover: {
            Lux: ["Марилька", "Эмаль", "ПВХ пленка"],
            Premium: ["Эмаль", "ПВХ пленка", "Шпон-марилька (за доплату)"],
            Comfort: ["металл", "Эмаль (за доплату)"]
        },
        glass: {
            Lux: ["синий зеркальный (калёный)", "йодовый зеркальный (калёный)", "йодовый (калёный)", "прозрачный (калёный)"],
            Premium: ["синий зеркальный (некалёный)", "йодовый зеркальный (некалёный)", "йодовый (некалёный)", "прозрачный (некалёный)", "синий зеркальный (калёный)", "йодовый зеркальный (калёный)", "йодовый (калёный)", "прозрачный (калёный)"],
            Comfort: ["синий зеркальный (некалёный)", "йодовый зеркальный (некалёный)", "йодовый (некалёный)", "прозрачный (некалёный)"]
        },
        lock: { Lux: ["Kale", "Guardian"], Premium: ["Kale", "Guardian", "Galeon"], Comfort: ["Argus", "Galeon"] }
    };

    function updateModels() {
        modelSelect.innerHTML = '<option value="">— выберите —</option>';
        if (price[categorySelect.value]) {
            Object.keys(price[categorySelect.value]).forEach(function (m) {
                const opt = document.createElement('option');
                opt.value = m; opt.textContent = m;
                if (m === @json(old('model', $contract->model))) opt.selected = true;
                modelSelect.appendChild(opt);
            });
        }
    }

    function updateFields() {
        const category = categorySelect.value;
        const model = modelSelect.value;
        const isFusion = fusionModels.includes(model);

        outerCoverSelect.innerHTML = '';
        let covers = isFusion ? config.fusion_outer_cover : config.default_outer_cover[category];
        // Если выбрана fusion-модель и в названии есть HPL, показывать только HPL
        if (isFusion && model.toLowerCase().includes('hpl')) {
            covers = covers.filter(v => v.toLowerCase().includes('hpl'));
        }
        if (covers) {
            covers.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v; opt.textContent = v;
                if (v === @json(old('outer_cover', $contract->outer_cover))) opt.selected = true;
                outerCoverSelect.appendChild(opt);
            });
        }

        innerTrimSelect.innerHTML = '<option value="">— выберите —</option>';
        config.inner_trim[category]?.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            if (v === @json(old('inner_trim', $contract->inner_trim))) opt.selected = true;
            innerTrimSelect.appendChild(opt);
        });

        innerCoverSelect.innerHTML = '<option value="">— выберите —</option>';
        config.inner_cover[category]?.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            if (v === @json(old('inner_cover', $contract->inner_cover))) opt.selected = true;
            innerCoverSelect.appendChild(opt);
        });
    }

    function updateGlass() {
        glassUnitSelect.innerHTML = '<option value="">— выберите —</option>';
        config.glass[categorySelect.value]?.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            if (v === @json(old('glass_unit', $contract->glass_unit))) opt.selected = true;
            glassUnitSelect.appendChild(opt);
        });
    }

    function updateLock() {
        lockSelect.innerHTML = '<option value="">— выберите —</option>';
        config.lock[categorySelect.value]?.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            if (v === @json(old('lock', $contract->lock))) opt.selected = true;
            lockSelect.appendChild(opt);
        });
    }

    function updateExtra() {
        extraInput.value = "";
        if (innerTrimSelect.value.includes('доплату')) extraInput.value += "внутренняя обшивка - шпон (доплата); ";
        if (innerCoverSelect.value.includes('доплату')) extraInput.value += innerCoverSelect.value + "; ";
        if (categorySelect.value === "Premium" && glassUnitSelect.value.includes('калёный')) extraInput.value += "калёный стеклопакет (доплата); ";
    }

    categorySelect.addEventListener('change', () => { 
        updateModels(); 
        updateFields(); 
        updateGlass(); 
        updateLock(); 
    });
    
    modelSelect.addEventListener('change', () => { 
        updateFields(); 
    });
    
    innerTrimSelect.addEventListener('change', updateExtra);
    innerCoverSelect.addEventListener('change', updateExtra);
    glassUnitSelect.addEventListener('change', updateExtra);

    // Инициализация значений при открытии формы
    updateModels();
    updateFields();
    updateGlass();
    updateLock();
});
</script>
@endpush
@endsection
