FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    libpq-dev \
    libzip-dev \
    wget \
    && docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

COPY . .

COPY .env .env

RUN chmod +x /app/entrypoint.sh

CMD ["./entrypoint.sh"]