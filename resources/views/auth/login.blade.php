@extends("layouts.auth")

@section("title", "Вход")

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
                        <img src="{{ asset('images/mds-doors-logo.svg') }}" alt="MDS Doors" height="60" class="me-3">
                    </div>
                    <div class="auth-brand-text">
                        <h1 class="auth-brand-title">MDS Doors</h1>
                        <p class="auth-brand-subtitle">Система управления договорами</p>
                    </div>
                </div>
                
                <div class="auth-hero">
                    <h2 class="auth-hero-title">Добро пожаловать обратно</h2>
                    <p class="auth-hero-subtitle">Войдите в систему для управления договорами и контроля бизнес-процессов</p>
                </div>
                
                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Аналитика</h3>
                            <p>Подробные отчеты и аналитика по договорам</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Эффективность</h3>
                            <p>Экономия времени на управлении документами</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <div class="auth-feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="auth-feature-content">
                            <h3>Мобильность</h3>
                            <p>Доступ к системе с любого устройства</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая секция - Форма -->
        <div class="auth-form-section">
            <div class="auth-form-wrapper">
                <div class="auth-form-header">
                    <h2 class="auth-form-title">Войти в систему</h2>
                    <p class="auth-form-subtitle">Введите ваши данные для доступа к системе</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf
                    
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
                        <label for="password" class="auth-form-label">Пароль</label>
                        <div class="auth-input-container">
                            <i class="fas fa-lock auth-input-icon"></i>
                            <input type="password" 
                                   class="auth-form-input @error('password') auth-form-input-error @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Введите ваш пароль"
                                   required>
                        </div>
                        @error('password')
                            <div class="auth-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="auth-form-options">
                        <label class="auth-checkbox">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="auth-checkbox-mark"></span>
                            <span class="auth-checkbox-text">Запомнить меня</span>
                        </label>
                    </div>

                    <div class="auth-form-actions">
                        <button type="submit" class="auth-form-button">
                            <span>Войти в систему</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>

                <div class="auth-form-footer">
                    <p class="auth-form-footer-text">
                        Нет аккаунта? 
                        <a href="{{ route('register') }}" class="auth-form-link">Зарегистрироваться</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
