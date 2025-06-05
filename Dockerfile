# syntax=docker/dockerfile:1

FROM php:8.3-apache

# Установка нужных PHP-расширений
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копируем проект
COPY . /var/www/html

# Работаем из директории Symfony
WORKDIR /var/www/html

# Установка зависимостей
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Настройки Apache
RUN a2enmod rewrite
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Symfony ENV
ENV APP_ENV=prod

# Порт для Render
EXPOSE 80