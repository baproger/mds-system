@extends('layouts.admin')

@section('title', 'Редактировать менеджера')

@section('content')
@php
    /** @var \App\Models\User $manager */
    $currentBranch = $manager->branch ?? auth()->user()->branch ?? null;
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Редактировать менеджера</h1>
                            <p class="page-subtitle">Обновление данных менеджера</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('rop.managers.update', $manager) }}" class="edit-form">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-edit"></i>
                            <span>Основная информация</span>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label required">
                                    <i class="fas fa-user"></i>
                                    Имя менеджера
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $manager->name) }}"
                                       required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label required">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $manager->email) }}"
                                       required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Телефон
                                </label>
                                <input type="tel"
                                       id="phone"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $manager->phone) }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Филиал — фиксируем филиалом РОПа/пользователя --}}
                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-building"></i>
                                    Филиал
                                </label>
                                <input type="text" class="form-control" value="{{ $currentBranch?->name }}" disabled>
                                <input type="hidden" name="branch_id" value="{{ old('branch_id', $currentBranch?->id) }}" required>
                                @error('branch_id') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Роль — только менеджер, редактирование недоступно РОПу --}}
                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-user-tag"></i>
                                    Роль
                                </label>
                                <input type="text" class="form-control" value="Менеджер" disabled>
                                <input type="hidden" name="role" value="manager">
                                @error('role') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Новый пароль (необязательно)
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Оставьте пустым, чтобы не менять">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Подтверждение пароля
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Повторите новый пароль">
                                @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!--<div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-info-circle"></i>
                            <span>Информация о ролях</span>
                        </div>

                        <div class="personnel-section">
                            <div class="personnel-item role-item" style="opacity:.55; pointer-events:none;">
                                <div class="personnel-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">РОП</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag rop-tag">Руководитель отдела продаж</span>
                                    </div>
                                </div>
                            </div>

                            <div class="personnel-item role-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">Менеджер</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag manager-tag">Менеджер по продажам</span>
                                    </div>
                                    <small class="form-text">В филиале может быть несколько менеджеров</small>
                                </div>
                            </div>
                        </div>
                    </div>-->

                    <div class="form-actions">
                        <a href="{{ route('rop.managers.index') }}" class="btn btn-cancel">
                            <i class="fas fa-arrow-left"></i>
                            Назад
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save"></i>
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* === Те же стили «админского» дизайна, что и в create === */
.edit-branch-container{max-width:1200px;margin:0 auto;padding:24px}
.page-header{margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid #e5e7eb}
.header-content{display:flex;align-items:center;gap:16px}
.header-icon{width:48px;height:48px;background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
.page-title{font-size:28px;font-weight:700;color:#6b7280;margin:0}
.page-subtitle{font-size:14px;color:#6b7280;margin:4px 0 0 0}
.form-section{background:#fff;border-radius:12px;padding:24px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid #f3f4f6;animation:fadeIn .3s ease-out}
.section-header{display:flex;align-items:center;gap:12px;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid #f3f4f6;font-weight:600;font-size:16px;color:#374151}
.section-header i{color:#fff !important;font-size:18px}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px}
.form-group{position:relative}
.form-label{display:flex;align-items:center;gap:8px;font-weight:600;font-size:14px;color:#374151;margin-bottom:8px}
.form-label.required::after{content:" *";color:#ef4444}
.form-label i{color:#6b7280;font-size:14px}
.form-control{width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:8px;font-size:14px;transition:.2s;background:#fafafa}
.form-control:focus{outline:none;border-color:#1ba4e9;background:#fff;box-shadow:0 0 0 3px rgba(27,164,233,.1)}
.form-control.is-invalid{border-color:#ef4444}
.invalid-feedback{color:#ef4444;font-size:12px;margin-top:4px}
.form-text{color:#6b7280;font-size:12px;margin-top:4px}
.personnel-section{display:flex;flex-direction:column;gap:16px}
.personnel-item{display:flex;align-items:flex-start;padding:16px;background:#fafafa;border-radius:8px;border:1px solid #f0f0f0;transition:.2s}
.personnel-item:hover{background:#f8f9fa;border-color:#e9ecef;transform:translateY(-1px)}
.personnel-icon{width:32px;height:32px;border-radius:6px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0}
.role-item .personnel-icon{background:#f3e8ff;color:#7c3aed}
.personnel-content{flex:1;min-width:0}
.personnel-title{font-weight:600;font-size:13px;color:#374151;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px}
.personnel-list{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px}
.personnel-tag{padding:4px 10px;border-radius:12px;font-size:12px;font-weight:500;display:inline-block;transition:.2s}
.rop-tag{background:#eef2ff;color:#7c3aed;border:1px solid #c7d2fe}
.manager-tag{background:#f1f5f9;color:#475569;border:1px solid #cbd5e1}
.form-actions{display:flex;justify-content:flex-end;gap:16px;padding-top:24px;border-top:1px solid #e5e7eb;margin-top:32px}
.btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;border:none;cursor:pointer;transition:.2s}
    .btn-cancel{background:#f3f4f6;color:#374151;border:1px solid #d1d5db}
    .btn-cancel:hover{background:#e5e7eb;transform:translateY(-1px)}
    .btn-save{color:#fff;}
.btn-save i{color:#fff !important;}
.btn-save{background:linear-gradient(135deg,#1ba4e9 0%,#ac76e3 100%);color:#fff;box-shadow:0 2px 4px rgba(27,164,233,.2)}
    .btn-save:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(27,164,233,.3)}
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
@media (max-width:768px){.edit-branch-container{padding:16px}.form-grid{grid-template-columns:1fr}.form-actions{flex-direction:column}.btn{width:100%;justify-content:center}}
</style>
@endsection