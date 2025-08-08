<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', Auth::user()->role === 'admin' ? 'Администратор сайта' : (Auth::user()->role === 'manager' ? 'Менеджер' : 'Панель управления')) - MDS Doors</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon">
                        <img src="{{ asset('images/logo.png') }}" alt="MDS Doors" height="32" class="me-2">
                    </div>
                    <div class="logo-text">
                        <div class="logo-title">MDS Doors</div>
                        <div class="logo-subtitle">Enterprise</div>
                    </div>
                    <div class="logo-toggle">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Управление</div>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    @elseif(Auth::user()->role === 'manager')
                    <a href="{{ route('manager.dashboard') }}" class="nav-item {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    @elseif(Auth::user()->role === 'rop')
                    <a href="{{ route('rop.dashboard') }}" class="nav-item {{ request()->routeIs('rop.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    @endif
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Пользователи</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <a href="{{ route('admin.branches.index') }}" class="nav-item {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Филиалы</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <a href="{{ route('admin.managers.index') }}" class="nav-item {{ request()->routeIs('admin.managers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Менеджеры</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    @endif
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.index' : (Auth::user()->role === 'manager' ? 'manager.contracts.index' : 'rop.contracts.index')) }}" class="nav-item {{ (request()->routeIs('admin.contracts.*') || request()->routeIs('manager.contracts.*') || request()->routeIs('rop.contracts.*')) ? 'active' : '' }}">
                        <i class="fas fa-file-contract"></i>
                        <span>Договоры</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.contracts.create' : (Auth::user()->role === 'manager' ? 'manager.contracts.create' : 'rop.contracts.create')) }}" class="nav-item {{ (request()->routeIs('admin.contracts.create') || request()->routeIs('manager.contracts.create') || request()->routeIs('rop.contracts.create')) ? 'active' : '' }}">
                        <i class="fas fa-plus"></i>
                        <span>Новый договор</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.calculator.index' : (Auth::user()->role === 'manager' ? 'manager.calculator.index' : 'rop.calculator.index')) }}" class="nav-item {{ (request()->routeIs('admin.calculator.*') || request()->routeIs('manager.calculator.*') || request()->routeIs('rop.calculator.*')) ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        <span>Калькулятор</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Система</div>
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.profile.show' : (Auth::user()->role === 'manager' ? 'manager.profile.show' : 'rop.profile.show')) }}" class="nav-item {{ (request()->routeIs('admin.profile.show') || request()->routeIs('manager.profile.show') || request()->routeIs('rop.profile.show')) ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Профиль</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Настройки</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <form method="POST" action="{{ route(Auth::user()->role === 'admin' ? 'admin.logout' : (Auth::user()->role === 'manager' ? 'manager.logout' : 'rop.logout')) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-item" style="width: 100%; text-align: left; background: none; border: none;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Выйти</span>
                        </button>
                    </form>
                </div>
            </nav>

            <!-- User profile -->
            <div class="sidebar-footer">
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
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('collapsed');
                document.querySelector('.main-content').classList.toggle('expanded');
            });
        }

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
    </script>
</body>
</html> 