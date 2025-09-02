<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', Auth::user()->role === 'admin' ? 'Администратор сайта' : (Auth::user()->role === 'manager' ? 'Менеджер' : 'Панель управления')) - MDS Doors</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Scrollable navigation area -->
            <div class="sidebar-scrollable">
                <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Управление</div>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    @elseif(Auth::user()->role === 'manager')
                    <a href="{{ route('manager.dashboard') }}" class="nav-item {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    @elseif(Auth::user()->role === 'rop')
                    <a href="{{ route('rop.dashboard') }}" class="nav-item {{ request()->routeIs('rop.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    @endif
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Пользователи</span>
                    </a>
                    <a href="{{ route('admin.branches.index') }}" class="nav-item {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Филиалы</span>
                    </a>
                    <a href="{{ route('admin.managers.index') }}" class="nav-item {{ request()->routeIs('admin.managers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Менеджеры</span>
                    </a>
                    @endif
                    @if(Auth::user()->role === 'rop')
                    <a href="{{ route('rop.managers.index') }}" class="nav-item {{ request()->routeIs('rop.managers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Менеджеры</span>
                    </a>
                    @endif
                    <a href="{{ route(Auth::user()->role . '.contracts.index') }}" class="nav-item {{ request()->routeIs(Auth::user()->role . '.contracts.*') ? 'active' : '' }}">
                        <i class="fas fa-file-contract"></i>
                        <span>Договоры</span>
                    </a>
                    @if(Auth::user()->role !== 'accountant')
                    <a href="{{ route(Auth::user()->role . '.contracts.create') }}" class="nav-item {{ request()->routeIs(Auth::user()->role . '.contracts.create') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i>
                        <span>Новый договор</span>
                    </a>
                    @endif
                    <a href="{{ route(Auth::user()->role . '.calculator.index') }}" class="nav-item {{ request()->routeIs(Auth::user()->role . '.calculator.*') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        <span>Калькулятор</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">CRM</div>
                    <a href="{{ route(Auth::user()->role . '.crm.demo') }}" class="nav-item {{ request()->routeIs(Auth::user()->role . '.crm.demo') ? 'active' : '' }}">
                        <i class="fas fa-rocket"></i>
                        <span>Обзор CRM</span>
                    </a>
                    <a href="{{ route(Auth::user()->role . '.crm.kanban') }}" class="nav-item {{ request()->routeIs(Auth::user()->role . '.crm.kanban') ? 'active' : '' }}">
                        <i class="fas fa-trello"></i>
                        <span>Канбан-доска</span>
                    </a>
                    <a href="{{ route(Auth::user()->role . '.crm.dashboard') }}" class="nav-item {{ request()->routeIs(Auth::user()->role . '.crm.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>CRM Дашборд</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Система</div>
                    <a href="{{ route(Auth::user()->role . '.settings.index') }}" class="nav-item {{ request()->routeIs(Auth::user()->role . '.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Настройки</span>
                    </a>
                    <button type="button" class="nav-item nav-item-logout" onclick="showLogoutModal()">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Выйти</span>
                    </button>
                </div>
                </nav>
            </div>

            <!-- Footer with user profile -->
            <div class="sidebar-footer">
                <!-- User profile -->
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-email">{{ Auth::user()->email }}</div>
                        <div class="user-role">
                            @if(Auth::user()->role === 'admin')
                                <span class="badge badge-primary">Администратор</span>
                            @elseif(Auth::user()->role === 'manager')
                                <span class="badge badge-secondary">Менеджер</span>
                            @elseif(Auth::user()->role === 'rop')
                                <span class="badge badge-info">РОП</span>
                            @elseif(Auth::user()->role === 'accountant')
                                <span class="badge badge-success">Бухгалтер</span>
                            @else
                                <span class="badge badge-info">{{ ucfirst(Auth::user()->role) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Main content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);

        // Scroll to active menu item
        document.addEventListener('DOMContentLoaded', function() {
            const activeItem = document.querySelector('.nav-item.active');
            if (activeItem) {
                const scrollableArea = document.querySelector('.sidebar-scrollable');
                if (scrollableArea) {
                    // Calculate position to center the active item
                    const itemTop = activeItem.offsetTop;
                    const scrollableHeight = scrollableArea.clientHeight;
                    const itemHeight = activeItem.clientHeight;
                    const scrollTop = itemTop - (scrollableHeight / 2) + (itemHeight / 2);
                    
                    scrollableArea.scrollTo({
                        top: Math.max(0, scrollTop),
                        behavior: 'smooth'
                    });
                }
            }
        });
    </script>

    <!-- Модальное окно подтверждения выхода -->
    <div id="logoutModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-sign-out-alt"></i></div>
                <h3 class="modal-title">Подтверждение выхода</h3>
                <p class="modal-subtitle">
                    Вы действительно хотите выйти из системы?
                </p>
            </div>
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="hideLogoutModal()">
                    <i class="fas fa-times"></i> Отмена
                </button>
                <form method="POST" action="{{ route(Auth::user()->role . '.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="modal-btn modal-btn-delete">
                        <i class="fas fa-sign-out-alt"></i> Выйти
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Функции для модального окна выхода
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }
        
        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }
        
        // Закрытие модального окна при клике вне его
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('logoutModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideLogoutModal();
                    }
                });
            }
        });
    </script>
</body>
</html> 