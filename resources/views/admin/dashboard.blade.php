@extends('layouts.admin')

@section('title', 'Панель управления')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="edit-branch-container">
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="page-title">Панель управления</h1>
                            <p class="page-subtitle">Обзор системы управления договорами</p>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->role === 'admin')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Общая статистика</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_branches'] }}</div>
                                <div class="stat-label">Филиалов</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_users'] }}</div>
                                <div class="stat-label">Пользователей</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-tie tag-icon"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_sales_staff'] }}</div>
                                <div class="stat-label">Продавцов</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_contracts'] }}</div>
                                <div class="stat-label">Договоров</div>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif(Auth::user()->role === 'manager')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>Моя статистика</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_contracts'] ?? 0 }}</div>
                                <div class="stat-label">Мои договоры</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['total_revenue'] ?? 0) }} ₸</div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['contracts_this_month'] ?? 0 }}</div>
                                <div class="stat-label">За этот месяц</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['average_contract_value'] ?? 0) }} ₸</div>
                                <div class="stat-label">Средний договор</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->role === 'admin')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-pie"></i>
                        <span>Детальная статистика</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_rop'] ?? $stats['total_rops'] ?? 0 }}</div>
                                <div class="stat-label">РОП</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_managers'] }}</div>
                                <div class="stat-label">Менеджеров</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['revenue_this_year'] ?? 0) }} ₸</div>
                                <div class="stat-label">Общий доход</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_branches'] > 0 ? round($stats['total_contracts'] / $stats['total_branches'], 1) : 0 }}</div>
                                <div class="stat-label">Среднее договоров</div>
                            </div>
                        </div>
                    </div>
                </div>

                @endif

                @if(Auth::user()->role === 'admin')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-building"></i>
                        <span>Статистика по филиалам</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($branches as $branch)
                            <div class="personnel-item branch-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">{{ $branch->name }}</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag branch-tag"><i class="fas fa-users tag-icon"></i>{{ $branch->users_count }} пользователей</span>
                                        <span class="personnel-tag manager-tag"><i class="fas fa-user-tie tag-icon"></i>{{ $branch->managers_count ?? 0 }} продавцов</span>
                                        <span class="personnel-tag contract-tag"><i class="fas fa-file-contract tag-icon"></i>{{ $branch->contracts_count }} договоров</span>
                                        <span class="personnel-tag code-tag">{{ $branch->code }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @elseif(Auth::user()->role === 'manager')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-user"></i>
                        <span>Мой филиал</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($branches as $branch)
                            <div class="personnel-item branch-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">{{ $branch->name }}</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag contract-tag"><i class="fas fa-file-contract tag-icon"></i>{{ $branch->contracts_count }} моих договоров</span>
                                        <span class="personnel-tag code-tag"><i class="fas fa-hashtag tag-icon"></i>{{ $branch->code }}</span>
                                        @if(isset($stats['last_contract_date']) && $stats['last_contract_date'])
                                            <span class="personnel-tag date-tag"><i class="fas fa-calendar-alt tag-icon"></i>Последний: {{ $stats['last_contract_date']->format('d.m.Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(Auth::user()->role === 'manager')   
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-chart-pie"></i>
                        <span>Мои показатели</span>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['contracts_this_year'] ?? 0 }}</div>
                                <div class="stat-label">За этот год</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['revenue_this_month'] ?? 0) }} ₸</div>
                                <div class="stat-label">Доход за месяц</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($stats['revenue_this_year'] ?? 0) }} ₸</div>
                                <div class="stat-label">Доход за год</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ ($stats['total_contracts'] ?? 0) > 0 ? round((($stats['contracts_this_month'] ?? 0) / ($stats['total_contracts'] ?? 1)) * 100) : 0 }}%</div>
                                <div class="stat-label">Активность</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($recent_contracts->count() > 0)
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-clock"></i>
                        <span>{{ Auth::user()->role === 'manager' ? 'Мои последние договоры' : 'Последние договоры' }}</span>
                    </div>
                    
                    <div class="personnel-section">
                        @foreach($recent_contracts as $contract)
                            <div class="personnel-item contract-item">
                                <div class="personnel-icon">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div class="personnel-content">
                                    <div class="personnel-title">{{ $contract->contract_number }}</div>
                                    <div class="personnel-list">
                                        <span class="personnel-tag client-tag"><i class="fas fa-user tag-icon"></i>{{ $contract->client }}</span>
                                        <span class="personnel-tag amount-tag"><i class="fas fa-money-bill-wave tag-icon"></i>{{ number_format($contract->order_total) }} ₸</span>
                                        <span class="personnel-tag date-tag"><i class="fas fa-calendar-alt tag-icon"></i>{{ $contract->date->format('d.m.Y') }}</span>
                                        @if(Auth::user()->role === 'admin' && $contract->user)
                                            <span class="personnel-tag manager-tag"><i class="fas fa-user-tie tag-icon"></i>{{ $contract->user->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(Auth::user()->role === 'admin')
                    @foreach(App\Models\Branch::with(['users' => function($q){ $q->where('role','manager')->withCount('contracts'); }])->get() as $branch)
                        @if($branch->users->count() > 0)
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-user-tie"></i>
                                <span>Менеджеры филиала "{{ $branch->name }}"</span>
                            </div>
                            
                            <div class="personnel-section">
                                @foreach($branch->users as $manager)
                                    <div class="personnel-item manager-item">
                                        <div class="personnel-icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="personnel-content">
                                            <div class="personnel-title">{{ $manager->name }}</div>
                                            <div class="personnel-list">
                                                <span class="personnel-tag contract-tag"><i class="fas fa-user-tie tag-icon"></i>{{ $manager->contracts_count }} договоров</span>
                                                <span class="personnel-tag branch-tag"><i class="fas fa-user-tie tag-icon"></i>{{ $branch->name }}</span>
                                                <span class="personnel-tag code-tag"><i class="fas fa-user-tie tag-icon"></i>{{ $branch->code }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 