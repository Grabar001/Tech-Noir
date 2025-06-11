FROM php:8.3-cli


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .


RUN composer install --no-dev --optimize-autoloader


EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]