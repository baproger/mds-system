<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Доступ запрещен | MDS Doors</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1ba4e9;
        }
        
        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #ffffff 0%, var(--bg-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .error-details {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .error-details h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: #fecaca;
        }
        
        .error-details p {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.5;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--bg-card);
            color: #1e293b;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: transparent;
            color: #1ba4e9;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .icon {
            font-size: 1.25rem;
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <h1 class="error-title">ДОСТУП ЗАПРЕЩЕН</h1>
        <p class="error-message">
            У вас нет прав для доступа к этому ресурсу
        </p>
        
        <div class="error-details">
            <h3><i class="fas fa-exclamation-triangle icon"></i> Причина ошибки</h3>
            <p>
                У вас недостаточно прав для доступа к этому ресурсу. Возможные причины:
            </p>
            <ul style="text-align: left; margin-top: 1rem; opacity: 0.9;">
                <li>Вы пытаетесь получить доступ к договору, который не принадлежит вам или вашему филиалу</li>
                <li>Ваша роль не позволяет выполнять это действие</li>
                <li>Договор находится в статусе, который не позволяет его редактировать</li>
                <li>Требуются дополнительные права доступа</li>
            </ul>
        </div>
        
        <div class="error-actions">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home icon"></i>
                На главную
            </a>
            
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-tachometer-alt icon"></i>
                        Админ панель
                    </a>
                @elseif(Auth::user()->role === 'manager')
                    <a href="{{ route('manager.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-tachometer-alt icon"></i>
                        Мой дашборд
                    </a>
                @elseif(Auth::user()->role === 'rop')
                    <a href="{{ route('rop.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-tachometer-alt icon"></i>
                        РОП дашборд
                    </a>
                @elseif(Auth::user()->role === 'production')
                    <a href="{{ route('production.crm.kanban') }}" class="btn btn-secondary">
                        <i class="fas fa-columns icon"></i>
                        Канбан доска
                    </a>
                @elseif(Auth::user()->role === 'accountant')
                    <a href="{{ route('accountant.crm.kanban') }}" class="btn btn-secondary">
                        <i class="fas fa-columns icon"></i>
                        Канбан доска
                    </a>
                @endif
            @endauth
            
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left icon"></i>
                Назад
            </a>
        </div>
    </div>
</body>
</html>
