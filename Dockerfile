
FROM php:8.2-cli


RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip


RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer


WORKDIR /app
COPY . .


RUN composer install --no-interaction --prefer-dist --optimize-autoloader


CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]