# Stage 1: Composer dependencies
FROM php:8.3-cli-alpine as vendor
# Composerを手動インストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app
COPY composer.json composer.lock ./
#RUN composer install --no-dev --optimize-autoloader

# artisan がないのでスクリプト実行を防ぐ
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Stage 2: Laravel application
FROM php:8.3-fpm-alpine

# 必要なPHP拡張機能
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Node.js, npm, etc（必要に応じて）
# RUN apk add --no-cache nodejs npm

WORKDIR /var/www/html

# アプリケーションコピー
COPY . .

# Composer install
COPY --from=vendor /app/vendor ./vendor

# Laravelのキャッシュを作成（ビルド時に可能であれば）
# RUN php artisan config:cache && \
#     php artisan route:cache && \
#     php artisan view:cache

# Dockerfile.prod または Dockerfile.nginx の既存のCOPY命令の後に追加
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
# パーミッション調整
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

# CMDを変更して、キャッシュ生成とphp-fpm起動を行う
# entrypoint.shのようなスクリプトを用意し、それをENTRYPOINTにするのがより柔軟です
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php-fpm