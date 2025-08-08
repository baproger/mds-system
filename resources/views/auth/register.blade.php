@extends("layouts.auth")

@section("title", "Регистрация")

@section("content")
<div class="auth-page">
    <!-- Фоновые элементы -->
    <div class="auth-background">
        <div class="auth-bg-gradient"></div>
        <div class="auth-bg-pattern"></div>
        <div class="auth-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="auth-content">
        <!-- Левая секция - Информация -->
        <div class="auth-info-section">
            <div class="auth-info-content">
                <div class="auth-brand">
                    <div class="auth-brand-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="auth-brand-text">
                        <h1 class="auth-brand-title">MDS Doors</h1>
                        <p class="auth-brand-subtitle">Система управления договорами</p>
                    </div>
                </div>
                
                <div class="auth-hero">
                    <h2 class="auth-hero-title">Держите ваш бизнес организованным</h2>
                    <p class="auth-hero-subtitle">Профессиональная система управления договорами для современного бизнеса</p>
                </div>
                
                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Безопасность</h3>
                            <p>Ваши данные защищены современными технологиями</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Аналитика</h3>
                            <p>Подробная аналитика и отчеты по договорам</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Команда</h3>
                            <p>Управление командой и правами доступа</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая секция - Форма -->
        <div class="auth-form-section">
            <div class="auth-form-wrapper">
                <div class="auth-form-header">
                    <h2 class="auth-form-title">Создать аккаунт</h2>
                    <p class="auth-form-subtitle">Присоединяйтесь к тысячам компаний, которые уже используют нашу систему</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf
                    
                    <div class="auth-form-group">
                        <label for="name" class="auth-form-label">Полное имя</label>
                        <div class="auth-input-container">
                            <i class="fas fa-user auth-input-icon"></i>
                            <input type="text" 
                                   class="auth-form-input @error('name') auth-form-input-error @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Введите ваше полное имя"
                                   required>
                        </div>
                        @error('name')
                            <div class="auth-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="auth-form-group">
                        <label for="email" class="auth-form-label">Email адрес</label>
                        <div class="auth-input-container">
                            <i class="fas fa-envelope auth-input-icon"></i>
                            <input type="email" 
                                   class="auth-form-input @error('email') auth-form-input-error @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Введите ваш email"
                                   required>
                        </div>
                        @error('email')
                            <div class="auth-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="auth-form-group">
                        <label for="branch_id" class="auth-form-label">Филиал</label>
                        <div class="auth-input-container">
                            <i class="fas fa-building auth-input-icon"></i>
                            <select class="auth-form-input @error('branch_id') auth-form-input-error @enderror" 
                                    id="branch_id" 
                                    name="branch_id" 
                                    required>
                                <option value="">Выберите ваш филиал</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('branch_id')
                            <div class="auth-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="password" class="auth-form-label">Пароль</label>
                            <div class="auth-input-container">
                                <i class="fas fa-lock auth-input-icon"></i>
                                <input type="password" 
                                       class="auth-form-input @error('password') auth-form-input-error @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Создайте надежный пароль"
                                       required>
                            </div>
                            @error('password')
                                <div class="auth-form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="auth-form-group">
                            <label for="password_confirmation" class="auth-form-label">Подтверждение</label>
                            <div class="auth-input-container">
                                <i class="fas fa-lock auth-input-icon"></i>
                                <input type="password" 
                                       class="auth-form-input" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Повторите пароль"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="auth-form-actions">
                        <button type="submit" class="auth-form-button">
                            <span>Создать аккаунт</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>

                <div class="auth-form-footer">
                    <p class="auth-form-footer-text">
                        Уже есть аккаунт? 
                        <a href="{{ route('login') }}" class="auth-form-link">Войти в систему</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
