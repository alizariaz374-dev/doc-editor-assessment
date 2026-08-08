FROM php:8.4-cli-alpine

RUN apk add --no-cache git unzip sqlite sqlite-dev libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 8080

CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}