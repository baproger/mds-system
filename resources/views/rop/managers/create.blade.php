@extends('layouts.admin')

@section('title', 'Добавить менеджера')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="fas fa-user-plus"></i>
                        Добавить менеджера в филиал "{{ $branch->name }}"
                    </h1>
                    <div class="page-actions">
                        <a href="{{ route('rop.managers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Назад к списку
                        </a>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Информация о менеджере</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('rop.managers.store') }}" method="POST" class="edit-form">
                        @csrf
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">ФИО *</label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required placeholder="Введите полное имя">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required placeholder="example@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="role" class="form-label">Роль *</label>
                                <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="">Выберите роль</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Менеджер</option>
                                </select>
                                <small class="form-text text-muted">РОП может создавать только менеджеров</small>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}" placeholder="+7 777 123 45 67">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Пароль *</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       required placeholder="Минимум 8 символов">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Подтверждение пароля *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                                       required placeholder="Повторите пароль">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-gradient-blue">
                                <i class="fas fa-save"></i> Создать менеджера
                            </button>
                            <a href="{{ route('rop.managers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Отмена
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.edit-form {
    max-width: 800px;
    margin: 0 auto;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #ef4444;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.btn-gradient-blue {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #1ba4e9;
    font-weight: 600;
}

.btn-gradient-blue:hover {
    filter: brightness(1.1);
    color: #1ba4e9;
}
</style>
@endsection
