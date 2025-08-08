@extends("layouts.auth")

@section("title", "Тест стилей")

@section("content")
<!-- Навигационная панель -->
<nav class="auth-navbar">
    <div class="auth-navbar-brand">
        <i class="fas fa-file-contract"></i>
        <span>Система договоров</span>
    </div>
    <div class="auth-navbar-menu">
        <div class="auth-navbar-item">
            <i class="fas fa-bars"></i>
            <span>Договоры</span>
        </div>
        <div class="auth-navbar-item">
            <i class="fas fa-plus"></i>
            <span>Новый договор</span>
        </div>
        <div class="auth-navbar-item active">
            <i class="fas fa-sign-in-alt"></i>
            <span>Войти</span>
        </div>
    </div>
</nav>

<!-- Основной контент -->
<div class="auth-main">
    <div class="auth-card">
        <div class="auth-card-header">
            <i class="fas fa-sign-in-alt"></i>
            <span>Тест стилей</span>
        </div>
        
        <div class="auth-card-body">
            <p style="color: #666; margin-bottom: 20px;">Если вы видите эту страницу с правильными стилями, значит CSS работает корректно!</p>
            
            <div class="auth-form-group">
                <label class="auth-form-label">Тестовое поле</label>
                <input type="text" class="auth-form-control" placeholder="Введите тестовый текст">
            </div>
            
            <div class="auth-form-group">
                <label class="auth-form-label">Выпадающий список</label>
                <select class="auth-form-control">
                    <option value="">Выберите опцию</option>
                    <option value="1">Опция 1</option>
                    <option value="2">Опция 2</option>
                    <option value="3">Опция 3</option>
                </select>
            </div>
            
            <button class="auth-btn auth-btn-primary">
                <i class="fas fa-check"></i>
                Тестовая кнопка
            </button>
        </div>
    </div>
</div>

<!-- Футер -->
<footer class="auth-footer">
    <div class="auth-footer-content">
        <div class="auth-footer-left">
            <i class="fas fa-copyright"></i>
            <span>2024 Система управления договорами. Все права защищены.</span>
        </div>
        <div class="auth-footer-right">
            <i class="fas fa-code"></i>
            <span>Разработано с</span>
            <i class="fas fa-heart"></i>
            <span>на Laravel</span>
        </div>
    </div>
</footer>
@endsection 