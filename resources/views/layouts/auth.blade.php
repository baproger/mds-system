<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Система управления договорами MDS Doors - профессиональное решение для вашего бизнеса">
    <meta name="keywords" content="договоры, управление, MDS Doors, система">
    <meta name="author" content="MDS Doors">
    <title>@yield("title", "Система договоров") - MDS Doors</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <style>
        /* Базовые стили для профессионального дизайна */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            overflow-x: hidden;
        }
        
        /* Плавные анимации */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Скрытие скроллбара для webkit браузеров */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
        

    </style>
</head>
<body>
    <!-- Контент страницы -->
    @yield("content")
    
    <!-- Дополнительные скрипты -->
    @stack("scripts")
</body>
</html> 