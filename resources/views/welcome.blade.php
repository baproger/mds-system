<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDS Doors - Управление дверным бизнесом</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #1ba4e9;
            --accent: #ec4899;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            
            /* Light theme */
            --bg-light: linear-gradient(135deg, var(--bg-secondary) 0%, var(--border-color) 50%, var(--bg-secondary) 100%);
            --bg-secondary-light: linear-gradient(135deg, #ffffff 0%, var(--bg-secondary) 100%);
            --text-light: #1e293b;
            --text-secondary-light: #64748b;
            --border-light: var(--border-color);
            
            /* Dark theme */
            --bg-dark: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            --bg-secondary-dark: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            --text-dark: var(--bg-secondary);
            --text-secondary-dark: #94a3b8;
            --border-dark: #475569;
            
            /* Active theme (default light) */
            --bg: var(--bg-light);
            --bg-secondary: var(--bg-secondary-light);
            --text: var(--text-light);
            --text-secondary: var(--text-secondary-light);
            --border: var(--border-light);
        }

        .dark-mode {
            --bg: var(--bg-dark);
            --bg-secondary: var(--bg-secondary-dark);
            --text: var(--text-dark);
            --text-secondary: var(--text-secondary-dark);
            --border: var(--border-dark);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            background-attachment: fixed;
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            padding: 1rem 0;
            position: sticky;
            top: 0;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 100;
        }
        
        .dark-mode .header {
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
        }

        .logo-img {
            width: 117px;
            height: 40px;
            object-fit: contain;
            border-radius: 8px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.7rem 1.4rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: #1ba4e9;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-secondary:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.2rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            background: var(--bg-secondary);
            color: var(--primary);
        }

        /* Hero Section */
        .hero {
            padding: 6rem 0 4rem;
        }
        
        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            margin-bottom: 4rem;
        }
        
        .hero-text {
            display: flex;
            flex-direction: column;
        }
        
        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .hero-3d-image {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            transition: transform 0.3s ease;
            animation: float3d 6s ease-in-out infinite;
        }
        
        .hero-3d-image:hover {
            animation-play-state: paused;
            transform: translateY(-10px) scale(1.02);
        }
        
        @keyframes float3d {
            0%, 100% { 
                transform: translateY(0px) rotateY(0deg); 
            }
            25% { 
                transform: translateY(-15px) rotateY(2deg); 
            }
            50% { 
                transform: translateY(-8px) rotateY(-1deg); 
            }
            75% { 
                transform: translateY(-20px) rotateY(3deg); 
            }
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .hero-content h1 .highlight {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .hero-graphic {
            position: relative;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-mockup {
            width: 100%;
            height: 340px;
            background: var(--bg-secondary);
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .dashboard-header {
            height: 50px;
            background: var(--bg);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .dashboard-tabs {
            display: flex;
            gap: 1.5rem;
        }

        .dashboard-tab {
            padding: 0.5rem 0;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            position: relative;
        }

        .dashboard-tab.active {
            color: var(--primary);
            font-weight: 600;
        }

        .dashboard-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary);
        }

        .dashboard-content {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            height: calc(100% - 50px);
        }

        .dashboard-card {
            background: var(--bg);
            border-radius: 8px;
            padding: 1.2rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
        }

        .card-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .card-chart {
            height: 40px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 6px;
            opacity: 0.7;
        }

        .floating-element {
            position: absolute;
            border-radius: 12px;
            background: var(--bg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            animation: float 6s ease-in-out infinite;
            border: 1px solid var(--border);
        }

        .floating-element-1 {
            top: 30px;
            right: -20px;
            animation-delay: 0s;
        }

        .floating-element-2 {
            bottom: 40px;
            left: -20px;
            animation-delay: 2s;
        }

        .element-icon {
            width: 36px;
            height: 36px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.8rem;
            color: var(--primary);
        }

        .element-content {
            display: flex;
            flex-direction: column;
        }

        .element-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
        }

        .element-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Features Section */
        .section {
            padding: 5rem 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 4rem;
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--bg);
            border-radius: 12px;
            padding: 2rem;
            text-align: left;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
            font-size: 1.5rem;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #1ba4e9;
            padding: 5rem 0;
            text-align: center;
            border-radius: 20px;
            margin: 4rem 0;
        }

        .cta-content {
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .cta-text {
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-light {
            background: var(--bg-card);
            color: var(--primary);
        }

        .btn-light:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
        }

        .btn-outline-light {
            background: transparent;
            color: #1ba4e9;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #1ba4e9;
        }

        /* Footer */
        .footer {
            background: var(--bg-secondary);
            color: var(--text);
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .footer-brand {
            grid-column: 1 / 2;
            max-width: 300px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem;
            text-decoration: none;
        }

        .footer-text {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }

        .social-link:hover {
            background: var(--primary);
            color: #1ba4e9;
            transform: translateY(-3px);
        }

        .footer-heading {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-copyright {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .footer-legal {
            display: flex;
            gap: 1.5rem;
        }

        .footer-legal a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: var(--primary);
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 3rem;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-3d-image {
                max-width: 80%;
            }

            .dashboard-mockup {
                height: 320px;
            }

            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta-title {
                font-size: 2rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
            
            .footer-brand {
                grid-column: 1 / 3;
                max-width: 100%;
                text-align: center;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-legal {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .btn {
                padding: 0.8rem 1.2rem;
                font-size: 0.85rem;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .footer-brand {
                grid-column: 1 / 2;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="MDS Doors" class="logo-img">
                </div>
                
                <div class="nav-links">
                    <a href="#features" class="nav-link">Возможности</a>
                    <a href="#solutions" class="nav-link">Решения</a>
                    <a href="#pricing" class="nav-link">Цены</a>
                    <a href="#about" class="nav-link">О нас</a>
                </div>
                
                <div class="nav-buttons">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Вход</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>
                        Управление дверным бизнесом с 
                        <span class="highlight">MDS Doors</span>
                    </h1>
                    <p>
                        Современная CRM-система для управления договорами, клиентами и продажами. 
                        Автоматизируйте процессы и увеличьте прибыль вашего бизнеса.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-rocket"></i>
                            Начать использовать
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-value">+87%</div>
                            <div class="stat-label">Эффективность менеджеров</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">24/7</div>
                            <div class="stat-label">Доступ к данным</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">500+</div>
                            <div class="stat-label">Довольных клиентов</div>
                        </div>
                    </div>
                </div>
                
                <div class="hero-image">
                    <img src="{{ asset('images/3Dhome.png') }}" alt="3D Дом" class="hero-3d-image">
                </div>
            </div>
            
            <div class="hero-graphic">
                <div class="dashboard-mockup">
                    <div class="dashboard-header">
                        <div class="dashboard-tabs">
                            <div class="dashboard-tab active">Договоры</div>
                            <div class="dashboard-tab">Клиенты</div>
                            <div class="dashboard-tab">Аналитика</div>
                        </div>
                    </div>
                    <div class="dashboard-content">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-title">Новые заказы</div>
                                <div class="card-value">24</div>
                            </div>
                            <div class="card-chart"></div>
                        </div>
                        <div class="dashboard-card">
                            <div class="card-header">
                                <div class="card-title">На производстве</div>
                                <div class="card-value">18</div>
                            </div>
                            <div class="card-chart"></div>
                        </div>
                    </div>
                </div>
                
                <div class="floating-element floating-element-1">
                    <div class="element-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="element-content">
                        <div class="element-title">Выполнено сегодня</div>
                        <div class="element-value">12 заказов</div>
                    </div>
                </div>
                
                <div class="floating-element floating-element-2">
                    <div class="element-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="element-content">
                        <div class="element-title">Конверсия</div>
                        <div class="element-value">68%</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section" id="features">
        <div class="container">
            <h2 class="section-title">Почему выбирают MDS Doors</h2>
            <p class="section-subtitle">
                Современные инструменты для эффективного управления вашим дверным бизнесом 
                и повышения прибыли
            </p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h3 class="feature-title">Управление договорами</h3>
                    <p class="feature-description">
                        Полный цикл работы с договорами от создания до завершения. 
                        Автоматизация процессов согласования и утверждения.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trello"></i>
                    </div>
                    <h3 class="feature-title">Канбан-доска</h3>
                    <p class="feature-description">
                        Визуальное управление воронкой продаж. Отслеживайте статусы 
                        и прогресс по каждому договору в реальном времени.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="feature-title">Калькулятор цен</h3>
                    <p class="feature-description">
                        Мгновенный расчет стоимости с учетом материалов, размеров 
                        и дополнительных опций. Интеграция с вашим прайс-листом.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Управление командой</h3>
                    <p class="feature-description">
                        Разграничение прав доступа для менеджеров, РОП и администраторов. 
                        Контроль эффективности работы каждого сотрудника.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Аналитика и отчеты</h3>
                    <p class="feature-description">
                        Детальная статистика по продажам, конверсиям и эффективности 
                        работы менеджеров. Визуализация ключевых показателей.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Мобильная адаптация</h3>
                    <p class="feature-description">
                        Полностью адаптивный интерфейс для работы на любых устройствах. 
                        Доступ к системе из любой точки мира в любое время.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section 
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Готовы оптимизировать свой бизнес?</h2>
                <p class="cta-text">
                    Присоединяйтесь к сотням компаний, которые уже используют MDS Doors 
                    для управления своими договорами и увеличения прибыли.
                </p>
                <div class="cta-buttons">
                    <a href="#" class="btn btn-light">
                        <i class="fas fa-rocket"></i>
                        Начать бесплатно
                    </a>
                </div>
            </div>
        </div>
    </section>-->

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#" class="footer-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="MDS Doors" class="logo-img">
                    </a>
                    <p class="footer-text">
                        Современная CRM-система для управления дверным бизнесом. 
                        Автоматизация процессов, увеличение прибыли и рост эффективности.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link">
                            <i class="fab fa-vk"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Ресурсы</h3>
                    <ul class="footer-links">
                        <li><a href="#">Блог</a></li>
                        <li><a href="#">Документация</a></li>
                        <li><a href="#">Вебинары</a></li>
                        <li><a href="#">Поддержка</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Компания</h3>
                    <ul class="footer-links">
                        <li><a href="#">О нас</a></li>
                        <li><a href="#">Контакты</a></li>
                        <li><a href="#">Вакансии</a></li>
                        <li><a href="#">Партнеры</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Поддержка</h3>
                    <ul class="footer-links">
                        <li><a href="#">Техподдержка</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Обучение</a></li>
                        <li><a href="#">Статус системы</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-copyright">
                    &copy; 2024 MDS Doors. Все права защищены.
                </div>
                <div class="footer-legal">
                    <a href="#">Политика конфиденциальности</a>
                    <a href="#">Условия использования</a>
                    <a href="#">Cookie</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle.querySelector('i');
        
        // Check for saved theme preference or respect OS preference
        if (localStorage.getItem('theme') === 'dark' || 
            (window.matchMedia('(prefers-color-scheme: dark)').matches && !localStorage.getItem('theme'))) {
            document.body.classList.add('dark-mode');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        }
        
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header scroll effect
        const header = document.querySelector('.header');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
            } else {
                header.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>