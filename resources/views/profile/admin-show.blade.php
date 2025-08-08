@extends('layouts.admin')
@section('title', 'Мой профиль')
@section('content')

<div class="profile-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Мой профиль</h1>
                <p class="page-subtitle">Управление личной информацией и настройками</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-edit"></i> Редактировать
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Основная информация -->
    <div class="info-section">
        <div class="section-header">
            <i class="fas fa-user"></i>
            <span>Основная информация</span>
        </div>
        
        <div class="profile-card">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <i class="fas fa-user"></i>
                </div>
                <div class="status-indicator online"></div>
            </div>
            <div class="profile-content">
                <div class="profile-title">{{ $user->name }}</div>
                <div class="profile-subtitle">
                    @switch($user->role)
                        @case('admin')
                            <span class="role-badge admin-badge">Администратор</span>
                            @break
                        @case('manager')
                            <span class="role-badge manager-badge">Менеджер</span>
                            @break
                        @case('rop')
                            <span class="role-badge rop-badge">РОП</span>
                            @break
                        @default
                            <span class="role-badge user-badge">{{ ucfirst($user->role) }}</span>
                    @endswitch
                </div>
                <div class="profile-details">
                    <div class="detail-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                    <div class="detail-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ $user->phone }}</span>
                    </div>
                    @endif
                    @if($user->role !== 'admin' && $user->branch)
                    <div class="detail-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $user->branch->name }}</span>
                    </div>
                    @endif
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>Регистрация: {{ $user->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика -->
    <div class="stats-section">
        <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <span>Статистика</span>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $user->contracts->count() }}</div>
                    <div class="stat-label">Всего договоров</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $user->contracts->where('date', '>=', now()->startOfMonth())->count() }}</div>
                    <div class="stat-label">За этот месяц</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($user->contracts->sum('order_total'), 0, ',', ' ') }} ₸</div>
                    <div class="stat-label">Общая сумма</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $user->created_at->diffForHumans() }}</div>
                    <div class="stat-label">В системе</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Последние договоры -->
    @if($user->contracts->count() > 0)
    <div class="recent-contracts-section">
        <div class="section-header">
            <i class="fas fa-history"></i>
            <span>Последние договоры</span>
        </div>
        
        <div class="contracts-list">
            @foreach($user->contracts->take(5) as $contract)
            <div class="contract-item">
                <div class="contract-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="contract-content">
                    <div class="contract-title">Договор №{{ $contract->contract_number }}</div>
                    <div class="contract-details">
                        <span class="contract-date">{{ $contract->date->format('d.m.Y') }}</span>
                        <span class="contract-amount">{{ number_format($contract->order_total, 0, ',', ' ') }} ₸</span>
                        <span class="contract-client">{{ $contract->client }}</span>
                    </div>
                </div>
                <div class="contract-actions">
                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

<style>
/* Стили для админского профиля */
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.page-header {
    background: #ffffff;
    color: #111827;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 4px 0;
}

.page-subtitle {
    font-size: 16px;
    color: #6b7280;
    margin: 0;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 12px 24px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
}

.info-section, .stats-section, .recent-contracts-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.section-header i {
    color: #667eea;
}

.profile-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px;
    background: #f9fafb;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.profile-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.profile-avatar {
    position: relative;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.status-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
}

.status-indicator.online {
    background: #10b981;
}

.profile-content {
    flex: 1;
}

.profile-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.profile-subtitle {
    margin-bottom: 16px;
}

.role-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white;
    display: inline-block;
}

.admin-badge {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.manager-badge {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.rop-badge {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.user-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.profile-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 12px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.detail-item:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.detail-item i {
    color: #667eea;
    width: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
    text-align: center;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    font-size: 32px;
    color: #667eea;
    margin-bottom: 16px;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.stat-label {
    color: #6b7280;
    font-size: 14px;
}

.contracts-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contract-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.contract-item:hover {
    background: #f3f4f6;
    transform: translateX(4px);
}

.contract-icon {
    font-size: 24px;
    color: #667eea;
    width: 40px;
    text-align: center;
}

.contract-content {
    flex: 1;
}

.contract-title {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.contract-details {
    display: flex;
    gap: 16px;
    font-size: 14px;
    color: #6b7280;
}

.contract-amount {
    color: #10b981;
    font-weight: 500;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
}

.btn-outline-primary {
    background: transparent;
    color: #667eea;
    border: 1px solid #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
}

/* Адаптивность для мобильных устройств */
@media (max-width: 768px) {
    .profile-container {
        padding: 1rem;
    }
    
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .profile-card {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-details {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .contract-details {
        flex-direction: column;
        gap: 4px;
    }
}

/* Специальные стили для админской панели */
@media (min-width: 1200px) {
    .profile-container {
        max-width: 1400px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>
