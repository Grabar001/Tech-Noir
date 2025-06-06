#!/bin/sh

# Установка зависимостей, если их нет
if [ ! -f /app/vendor/autoload_runtime.php ]; then
  echo "Installing dependencies..."
  composer install --no-dev --optimize-autoloader
fi

# Запуск PHP-сервера
php -S 0.0.0.0:8000 -t public