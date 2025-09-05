<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title", "Система договоров")</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
</head>
<body class="bg-light">
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route("contracts.index") }}">
                <img src="{{ asset('images/logo.png') }}" alt="MDS Doors" height="40">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contracts.index') ? 'active' : '' }}" 
                           href="{{ route("contracts.index") }}">
                            <i class="fas fa-list me-2"></i> Договоры
                        </a>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.contracts.create') || request()->routeIs('manager.contracts.create') || request()->routeIs('rop.contracts.create') ? 'active' : '' }}" 
                           href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.create' : (Auth::user()->role === 'manager' ? 'manager.contracts.create' : 'rop.contracts.create')) }}">
                            <i class="fas fa-plus me-2"></i> Новый договор
                        </a>
                    </li>
                    @endif
                    @auth
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager' || Auth::user()->role === 'rop')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs(Auth::user()->role . '.dashboard') ? 'active' : '' }}" 
                               href="{{ route(Auth::user()->role . '.dashboard') }}">
                                <i class="fas fa-chart-bar me-2"></i> Статистика
                            </a>
                        </li>
                        @endif
                        
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager' || Auth::user()->role === 'rop')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-tachometer-alt me-2"></i> CRM
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs(Auth::user()->role . '.crm.demo') ? 'active' : '' }}" 
                                       href="{{ route(Auth::user()->role . '.crm.demo') }}">
                                        <i class="fas fa-rocket me-2"></i> Обзор CRM
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs(Auth::user()->role . '.crm.kanban') ? 'active' : '' }}" 
                                       href="{{ route(Auth::user()->role . '.crm.kanban') }}">
                                        <i class="fas fa-trello me-2"></i> Канбан-доска
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs(Auth::user()->role . '.crm.dashboard') ? 'active' : '' }}" 
                                       href="{{ route(Auth::user()->role . '.crm.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Дашборд
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    @endauth
                </ul>
                
                @auth
                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                               id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                <li>
                                    <a class="dropdown-item" href="{{ route(Auth::user()->role . '.dashboard') }}">
                                        <i class="fas fa-shield-alt me-2"></i> Панель управления
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @if(!in_array(Auth::user()->role, ['production', 'accountant']))
                                <li>
                                    <a class="dropdown-item" href="{{ route(Auth::user()->role . '.profile.show') }}">
                                        <i class="fas fa-user me-2"></i> Профиль
                                    </a>
                                </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog me-2"></i> Настройки
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route("logout") }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Выйти
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="navbar-nav ms-auto">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Войти
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="py-4">
        <div class="container">
            <!-- Уведомления -->
            @if(session("success"))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session("success") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session("error"))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session("error") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session("warning"))
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session("warning") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session("info"))
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session("info") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Ошибка!</strong> Пожалуйста, исправьте следующие ошибки:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Контент страницы -->
            @yield("content")
        </div>
    </main>

    <!-- Футер -->
    <footer class="bg-white border-top mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        <i class="fas fa-copyright me-1"></i>
                        2024 Система управления договорами. Все права защищены.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        <i class="fas fa-code me-1"></i>
                        Разработано с <i class="fas fa-heart text-danger"></i> на Laravel
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Дополнительные скрипты -->
    @stack("scripts")
    
    <!-- Глобальные скрипты -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Автоматическое скрытие алертов через 5 секунд
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
        
        // Подтверждение для всех форм удаления
        const deleteForms = document.querySelectorAll('form[method="POST"]');
        deleteForms.forEach(form => {
            if (form.querySelector('input[name="_method"][value="DELETE"]')) {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Вы уверены, что хотите выполнить это действие?')) {
                        e.preventDefault();
                    }
                });
            }
        });
        
        // Улучшенная навигация
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });
    </script>

    <style>
    /* Современные стили для менеджеров */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .navbar {
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .navbar-brand {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .brand-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .nav-link {
        font-weight: 500;
        padding: 0.75rem 1rem !important;
        border-radius: 8px;
        margin: 0 0.25rem;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-1px);
    }

    .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        font-weight: 600;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .dropdown-menu {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin: 0.25rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }

    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, #1ba4e9 0%, #ac76e3 100%);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        color: white;
    }

    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .form-control {
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #1ba4e9;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Анимации */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .container {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .navbar-brand {
            font-size: 1.25rem;
        }
        
        .brand-icon {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }
    }
    </style>
</body>
</html>
