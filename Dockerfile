# syntax=docker/dockerfile:1

FROM php:8.2-fpm


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app


COPY composer.json composer.lock ./


RUN composer install --no-dev --optimize-autoloader --no-interaction


COPY . .


RUN chmod +x /app/entrypoint.sh

ENTRYPOINT ["/app/entrypoint.sh"]

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]