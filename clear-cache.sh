#!/bin/bash

echo "🧹 Очистка всех кэшей..."

# Очистка кэша Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Пересборка ассетов
npm run build

echo "✅ Все кэши очищены!"
echo "🔄 Перезагрузите страницу в браузере (Ctrl+F5)" 