FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libpng-dev libonig-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN cp .env.example .env

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# --no-scripts evita que se ejecute "php artisan package:discover" durante el build
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

RUN npm install && npm run build

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD php artisan package:discover --ansi && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan storage:link && \
    php artisan serve --host=0.0.0.0 --port=8000
