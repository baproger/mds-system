<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Демо компонента ролей - MDS Doors</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            font-family: var(--font-family);
        }
        
        .demo-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .demo-header {
            text-align: center;
            color: var(--white);
            margin-bottom: 40px;
        }
        
        .demo-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .demo-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .demo-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .section-title {
            color: var(--white);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 12px;
            opacity: 0.9;
        }
        
        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .feature-description {
            font-size: 0.9rem;
            opacity: 0.8;
            line-height: 1.4;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: var(--white);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="demo-container">
        <a href="{{ route('admin.dashboard') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Вернуться к панели управления
        </a>
        
        <div class="demo-header">
            <h1 class="demo-title">Компонент отображения ролей</h1>
            <p class="demo-subtitle">Современный, анимированный UI компонент для отображения ролей пользователей системы</p>
        </div>
        
        <div class="demo-section">
            <h2 class="section-title">Демонстрация компонента</h2>
            @include('components.role-display')
        </div>
        
        <div class="demo-section">
            <h2 class="section-title">Возможности</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="feature-title">Цветовая кодировка</div>
                    <div class="feature-description">Каждая роль имеет уникальную цветовую схему с градиентными фонами</div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="feature-title">Адаптивный дизайн</div>
                    <div class="feature-description">Полностью адаптивная верстка, работающая на всех размерах экранов</div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div class="feature-title">Плавные анимации</div>
                    <div class="feature-description">Эффекты при наведении, анимации кликов и плавные переходы</div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-universal-access"></i>
                    </div>
                    <div class="feature-title">Доступность</div>
                    <div class="feature-description">Навигация с клавиатуры и состояния фокуса для лучшей доступности</div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-moon"></i>
                    </div>
                    <div class="feature-title">Темная тема</div>
                    <div class="feature-description">Автоматическая поддержка темной темы в соответствии с системными настройками</div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="feature-title">Чистый код</div>
                    <div class="feature-description">Хорошо структурированный HTML, CSS и JavaScript с современными практиками</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 