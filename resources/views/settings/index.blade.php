@extends('layouts.admin')

@section('title', 'Настройки')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <!-- Заголовок -->
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Настройки</h1>
                            <p class="page-subtitle">Профиль, безопасность и персонализация интерфейса</p>
                        </div>
                    </div>
                </div>

                {{-- Flash-сообщения --}}
                @if(session('success'))
                    <div class="form-section" style="border-left:4px solid #10b981">
                        <div class="section-header" style="border-bottom:none; padding-bottom:0; margin-bottom:0">
                            <i class="fas fa-check-circle" style="color:#10b981"></i>
                            <span style="color:#065f46">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="form-section" style="border-left:4px solid #ef4444">
                        <div class="section-header" style="border-bottom:none; padding-bottom:0; margin-bottom:0">
                            <i class="fas fa-exclamation-triangle" style="color:#ef4444"></i>
                            <span style="color:#991b1b">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Профиль -->
                <div class="form-section">
                    <div class="section-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-user"></i>
                            <span>Профиль</span>
                        </div>
                        <span class="form-text">Основная информация о пользователе</span>
                    </div>

                    <form action="{{ route(Auth::user()->role . '.settings.profile') }}" method="POST" class="search-form">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-id-badge"></i> Имя
                                </label>
                                <input id="name" name="name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input id="email" name="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i> Телефон
                                </label>
                                <input id="phone" name="phone" type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save"></i> Сохранить профиль
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Безопасность -->
                <div class="form-section">
                    <div class="section-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-lock"></i>
                            <span>Безопасность</span>
                        </div>
                        <span class="form-text">Смена пароля</span>
                    </div>

                    <form action="{{ route(Auth::user()->role . '.settings.password') }}" method="POST" class="search-form">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key"></i> Текущий пароль
                                </label>
                                <input id="current_password" name="current_password" type="password"
                                       class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-shield-alt"></i> Новый пароль
                                </label>
                                <input id="password" name="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-check-double"></i> Подтверждение пароля
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-sync-alt"></i> Сменить пароль
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Интерфейс -->
                <div class="form-section">
                    <div class="section-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-palette"></i>
                            <span>Интерфейс</span>
                        </div>
                        <span class="form-text">Персонализация внешнего вида</span>
                    </div>

                    <form action="{{ route(Auth::user()->role . '.settings.preferences') }}" method="POST" class="search-form">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="theme" class="form-label">
                                    <i class="fas fa-adjust"></i> Тема оформления
                                </label>
                                <select id="theme" name="theme" class="form-control">
                                    <option value="light" {{ session('user_preferences.theme', 'light') === 'light' ? 'selected' : '' }}>Светлая</option>
                                    <option value="dark"  {{ session('user_preferences.theme') === 'dark' ? 'selected' : '' }}>Тёмная</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="language" class="form-label">
                                    <i class="fas fa-language"></i> Язык интерфейса
                                </label>
                                <select id="language" name="language" class="form-control">
                                    <option value="ru" {{ session('user_preferences.language', 'ru') === 'ru' ? 'selected' : '' }}>Русский</option>
                                    <option value="en" {{ session('user_preferences.language') === 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save"></i> Сохранить настройки
                            </button>
                        </div>
                    </form>
                </div>

                @if(Auth::user()->role === 'admin')
                <!-- Система (только админ) -->
                <div class="form-section">
                    <div class="section-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-server"></i>
                            <span>Система</span>
                        </div>
                        <span class="form-text">Настройки компании и политики</span>
                    </div>

                    <form action="{{ route('admin.settings.system') }}" method="POST" class="search-form">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="company_name" class="form-label">
                                    <i class="fas fa-building"></i> Название компании
                                </label>
                                <input id="company_name" name="company_name" type="text"
                                       class="form-control"
                                       value="{{ session('system_settings.company_name', 'MDS Doors') }}">
                            </div>

                            <div class="form-group">
                                <label for="company_email" class="form-label">
                                    <i class="fas fa-at"></i> Email компании
                                </label>
                                <input id="company_email" name="company_email" type="email"
                                       class="form-control"
                                       value="{{ session('system_settings.company_email') }}">
                            </div>

                            <div class="form-group">
                                <label for="company_phone" class="form-label">
                                    <i class="fas fa-phone-alt"></i> Телефон компании
                                </label>
                                <input id="company_phone" name="company_phone" type="text"
                                       class="form-control"
                                       value="{{ session('system_settings.company_phone') }}">
                            </div>

                            <div class="form-group">
                                <label for="auto_logout_minutes" class="form-label">
                                    <i class="fas fa-hourglass-half"></i> Автовыход (мин)
                                </label>
                                <input id="auto_logout_minutes" name="auto_logout_minutes" type="number" min="5" max="480"
                                       class="form-control"
                                       value="{{ session('system_settings.auto_logout_minutes', 30) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tools"></i> Доп. функции
                                </label>
                                <label class="personnel-tag manager-tag" style="cursor:pointer">
                                    <input type="checkbox" name="backup_enabled" {{ session('system_settings.backup_enabled') ? 'checked' : '' }}>
                                    Автоматическое резервное копирование
                                </label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-cog"></i> Сохранить системные настройки
                            </button>
                        </div>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Лёгкие правки под чекбоксы, чтобы вписать их в общий стиль --}}
<style>
/* ========== БАЗОВЫЙ АДМИН-СТИЛЬ ========== */
.edit-branch-container{max-width:1200px;margin:0 auto;padding:24px}
.page-header{margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid #e5e7eb}
.header-content{display:flex;align-items:center;gap:16px}
.header-icon{width:48px;height:48px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
.page-title{font-size:28px;font-weight:700;color:#111827;margin:0}
.page-subtitle{font-size:14px;color:#6b7280;margin:4px 0 0 0}

.form-section{background:#fff;border-radius:12px;padding:24px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid #f3f4f6;animation:fadeIn .3s ease-out}
.section-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid #f3f4f6;font-weight:600;font-size:16px;color:#374151}
.section-header i{color:#667eea;font-size:18px}
.section-actions{display:flex;gap:12px}

.search-form .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px}
.form-group{position:relative}
.form-label{display:flex;align-items:center;gap:8px;font-weight:600;font-size:14px;color:#374151;margin-bottom:8px}
.form-label i{color:#6b7280;font-size:14px}
.form-text{color:#6b7280;font-size:12px}

.form-control{width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:8px;font-size:14px;transition:.2s;background:#fafafa}
.form-control:focus{outline:none;border-color:#667eea;background:#fff;box-shadow:0 0 0 3px rgba(102,126,234,.1)}
.form-control.is-invalid{border-color:#ef4444}
.invalid-feedback{color:#ef4444;font-size:12px;margin-top:4px}

.form-actions{display:flex;gap:12px;flex-wrap:wrap;padding-top:16px}
.btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;border:none;cursor:pointer;transition:.2s}
.btn-sm{padding:8px 12px;font-size:12px}
.btn-cancel{background:#f3f4f6;color:#374151;border:1px solid #d1d5db}
.btn-cancel:hover{background:#e5e7eb;transform:translateY(-1px)}
.btn-save{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;box-shadow:0 2px 4px rgba(102,126,234,.2)}
.btn-save:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(102,126,234,.3)}
.btn-danger{background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:#fff;box-shadow:0 2px 4px rgba(239,68,68,.2)}
.btn-danger:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(239,68,68,.3)}

/* Статистика/карточки (если используешь) */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);border:1px solid #e2e8f0;border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;transition:.2s}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.1)}
.stat-icon{width:48px;height:48px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
.stat-number{font-size:16px;font-weight:600;color:#111827;margin-bottom:4px}
.stat-label{font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;font-weight:600}

/* Теги/списки (используем как чекбоксы тоже) */
.personnel-section{display:flex;flex-direction:column;gap:16px}
.personnel-item{display:flex;align-items:flex-start;padding:16px;background:#fafafa;border-radius:8px;border:1px solid #f0f0f0;transition:.2s}
.personnel-item:hover{background:#f8f9fa;border-color:#e9ecef;transform:translateY(-1px)}
.personnel-icon{width:32px;height:32px;border-radius:6px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0}
.manager-item .personnel-icon{background:#f3e8ff;color:#7c3aed}
.personnel-content{flex:1;min-width:0}
.personnel-title{font-weight:600;font-size:16px;color:#111827;margin-bottom:8px}
.personnel-list{display:flex;flex-wrap:wrap;gap:6px}
.personnel-tag{padding:6px 12px;border-radius:8px;font-size:12px;font-weight:500;display:inline-flex;align-items:center;gap:8px;transition:.2s;border:1px solid}
.rop-tag{background:#eef2ff;color:#7c3aed;border-color:#c7d2fe}
.manager-tag{background:#f1f5f9;color:#475569;border-color:#cbd5e1}
.branch-tag{background:#f0f9ff;color:#0369a1;border-color:#bae6fd}
.contract-tag,.amount-tag{background:#f0fdf4;color:#166534;border-color:#bbf7d0}
.month-tag{background:#f3f4f6;color:#6b7280;border-color:#d1d5db}
.email-tag{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
.tag-icon{margin-right:6px;opacity:.85}

/* Модальное (админ) — если нужно для подтверждений */
.modal-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.6);z-index:99999;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.modal-content{background:#fff;border-radius:16px;padding:32px;max-width:450px;width:90%;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);border:1px solid rgba(0,0,0,.1);animation:modalSlideIn .3s ease-out}
@keyframes modalSlideIn{from{opacity:0;transform:translateY(-20px) scale(.95)}to{opacity:1;transform:translateY(0) scale(1)}}
.modal-header{text-align:center;margin-bottom:28px;display:inline}
.modal-icon{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);color:#d97706;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:24px;box-shadow:0 4px 12px rgba(217,119,6,.2)}
.modal-title{font-size:20px;font-weight:700;color:#111827;margin-bottom:12px;line-height:1.3}
.modal-subtitle{color:#6b7280;font-size:15px;line-height:1.6;margin:0}
.modal-actions{display:flex;gap:16px;justify-content:center;margin-top:32px}
.modal-btn{padding:12px 24px;border-radius:10px;font-weight:600;font-size:15px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:.2s;min-width:120px;justify-content:center}
.modal-btn-cancel{background:#f3f4f6;color:#374151;border:1px solid #e5e7eb}
.modal-btn-cancel:hover{background:#e5e7eb;color:#111827;transform:translateY(-1px);box-shadow:0 4px 8px rgba(0,0,0,.1)}
.modal-btn-delete{background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:#fff;box-shadow:0 4px 12px rgba(239,68,68,.3)}
.modal-btn-delete:hover{background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);transform:translateY(-1px);box-shadow:0 6px 16px rgba(239,68,68,.4)}

/* Анимации */
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

/* ========== ДОП. СТИЛИ ДЛЯ СТРАНИЦЫ “НАСТРОЙКИ” ========== */
.settings-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px;margin-top:24px}
.settings-card{background:#fff;border-radius:16px;box-shadow:0 4px 6px -1px rgba(0,0,0,.1);border:1px solid #e5e7eb;overflow:hidden;transition:.3s}
.settings-card:hover{box-shadow:0 10px 15px -3px rgba(0,0,0,.1);transform:translateY(-2px)}
.settings-card-header{background:linear-gradient(135deg,#f8fafc 0%,#e2e8f0 100%);padding:24px;border-bottom:1px solid #e5e7eb}
.settings-card-body{padding:24px}
.settings-icon{width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:16px}
.settings-title{font-size:20px;font-weight:700;color:#111827;margin:0 0 8px}
.settings-subtitle{color:#6b7280;font-size:14px;margin:0}

/* Чекбоксы как теги */
.personnel-tag input[type="checkbox"]{margin-right:6px;accent-color:#667eea}

/* ========== АДАПТИВ ========== */
@media (max-width:1024px){
  .settings-grid{grid-template-columns:repeat(auto-fit,minmax(320px,1fr))}
}
@media (max-width:768px){
  .edit-branch-container{padding:16px}
  .search-form .form-grid,.form-grid{grid-template-columns:1fr}
  .form-actions{flex-direction:column}
  .btn{width:100%;justify-content:center}
  .settings-card-header,.settings-card-body{padding:16px}
}
</style>
@endsection