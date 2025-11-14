@extends('layouts.admin')

@section('page-title', 'Настройки')

@section('content')
<div class="dashboard-grid">
    <!-- Page Header -->
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Настройки</h1>
            <p class="dashboard-subtitle">Управление настройками системы и профилем</p>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="summary-grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-8);">
        <!-- Profile Settings -->
        <div class="cards-section">
            <div class="cards-header">
                <h2 class="cards-title">Профиль</h2>
            </div>
            
            <div style="padding: var(--space-6);">
                <div style="display: flex; align-items: center; gap: var(--space-4); margin-bottom: var(--space-6);">
                    <div class="user-avatar" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--gray-800); margin-bottom: var(--space-1);">
                            {{ Auth::user()->name }}
                        </h3>
                        <p style="color: var(--gray-600); margin-bottom: var(--space-2);">{{ Auth::user()->email }}</p>
                        <span class="card-status active">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
                
                <form style="display: flex; flex-direction: column; gap: var(--space-4);">
                    <div>
                        <label style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: var(--space-2);">
                            Имя
                        </label>
                        <input type="text" value="{{ Auth::user()->name }}" 
                               style="width: 100%; padding: var(--space-3); border: 1px solid var(--gray-300); border-radius: var(--radius-md); background: var(--bg-card);">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: var(--space-2);">
                            Email
                        </label>
                        <input type="email" value="{{ Auth::user()->email }}" 
                               style="width: 100%; padding: var(--space-3); border: 1px solid var(--gray-300); border-radius: var(--radius-md); background: var(--bg-card);">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: var(--space-2);">
                            Телефон
                        </label>
                        <input type="tel" value="{{ Auth::user()->phone ?? '' }}" 
                               style="width: 100%; padding: var(--space-3); border: 1px solid var(--gray-300); border-radius: var(--radius-md); background: var(--bg-card);">
                    </div>
                    
                    <button type="submit" class="add-card-btn" style="margin-top: var(--space-4);">
                        <i class="fas fa-save"></i>
                        Сохранить изменения
                    </button>
                </form>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="cards-section">
            <div class="cards-header">
                <h2 class="cards-title">Безопасность</h2>
            </div>
            
            <div style="padding: var(--space-6);">
                <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-4); border: 1px solid var(--gray-200); border-radius: var(--radius-md);">
                        <div>
                            <h4 style="font-weight: 600; color: var(--gray-800); margin-bottom: var(--space-1);">Двухфакторная аутентификация</h4>
                            <p style="font-size: 0.875rem; color: var(--gray-600);">Дополнительная защита аккаунта</p>
                        </div>
                        <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                            <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--gray-300); transition: .4s; border-radius: 24px;">
                                <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                            </span>
                        </label>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-4); border: 1px solid var(--gray-200); border-radius: var(--radius-md);">
                        <div>
                            <h4 style="font-weight: 600; color: var(--gray-800); margin-bottom: var(--space-1);">Уведомления по email</h4>
                            <p style="font-size: 0.875rem; color: var(--gray-600);">Получать уведомления о важных событиях</p>
                        </div>
                        <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                            <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--primary-blue); transition: .4s; border-radius: 24px;">
                                <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 29px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                            </span>
                        </label>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-4); border: 1px solid var(--gray-200); border-radius: var(--radius-md);">
                        <div>
                            <h4 style="font-weight: 600; color: var(--gray-800); margin-bottom: var(--space-1);">SMS уведомления</h4>
                            <p style="font-size: 0.875rem; color: var(--gray-600);">Критические уведомления по SMS</p>
                        </div>
                        <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                            <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--gray-300); transition: .4s; border-radius: 24px;">
                                <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Settings -->
    <div class="cards-section">
        <div class="cards-header">
            <h2 class="cards-title">Настройки системы</h2>
        </div>
        
        <div style="padding: var(--space-6);">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-6);">
                <div>
                    <h4 style="font-weight: 600; color: var(--gray-800); margin-bottom: var(--space-4);">Внешний вид</h4>
                    <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-700);">Темная тема</span>
                            <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--gray-300); transition: .4s; border-radius: 24px;">
                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                                </span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-700);">Компактный режим</span>
                            <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--gray-300); transition: .4s; border-radius: 24px;">
                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="font-weight: 600; color: var(--gray-800); margin-bottom: var(--space-4);">Уведомления</h4>
                    <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-700);">Новые договоры</span>
                            <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--primary-blue); transition: .4s; border-radius: 24px;">
                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 29px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                                </span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-700);">Обновления системы</span>
                            <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--primary-blue); transition: .4s; border-radius: 24px;">
                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 29px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="font-weight: 600; color: var(--gray-800); margin-bottom: var(--space-4);">Данные</h4>
                    <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-700);">Автосохранение</span>
                            <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--primary-blue); transition: .4s; border-radius: 24px;">
                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 29px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                                </span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-700);">Резервное копирование</span>
                            <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--primary-blue); transition: .4s; border-radius: 24px;">
                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 29px; bottom: 3px; background-color: #1ba4e9; transition: .4s; border-radius: 50%;"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="cards-section" style="border: 1px solid #fee2e2; background: #fef2f2;">
        <div class="cards-header">
            <h2 class="cards-title" style="color: #991b1b;">Опасная зона</h2>
        </div>
        
        <div style="padding: var(--space-6);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="font-weight: 600; color: #991b1b; margin-bottom: var(--space-2);">Удалить аккаунт</h4>
                    <p style="color: #dc2626; font-size: 0.875rem;">Это действие нельзя отменить. Все данные будут удалены навсегда.</p>
                </div>
                <button style="padding: var(--space-2) var(--space-4); background: #dc2626; color: #1ba4e9; border: none; border-radius: var(--radius-md); font-weight: 500; cursor: pointer;">
                    Удалить аккаунт
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    padding: var(--space-4);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-6);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    font-size: 0.875rem;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert i {
    font-size: 1rem;
}

.fade-out {
    animation: fadeOut 0.3s ease forwards;
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-10px); }
}
</style>
@endsection 