FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist 2>/dev/null || true

FROM php:8.2-fpm-alpine AS production
RUN apk add --no-cache postgresql-dev redis icu-dev linux-headers libpng-dev libjpeg-turbo-dev $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql intl opcache bcmath gd \
    && pecl install redis && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
WORKDIR /var/www/html
COPY --from=vendor /app/vendor ./vendor
COPY . .
EXPOSE 9000
CMD ["php-fpm"]

FROM php:8.2-fpm-alpine AS development
RUN apk add --no-cache postgresql-dev redis icu-dev linux-headers libpng-dev libjpeg-turbo-dev $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql intl opcache bcmath gd \
    && pecl install redis xdebug && docker-php-ext-enable redis xdebug \
    && apk del $PHPIZE_DEPS
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
WORKDIR /var/www/html
EXPOSE 9000
CMD ["/entrypoint.sh"]
