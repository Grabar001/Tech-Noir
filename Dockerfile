FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader && composer dump-env prod
RUN mkdir -p var/cache var/log && chmod -R 777 var

EXPOSE 8000
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t public"]