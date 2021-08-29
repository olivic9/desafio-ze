FROM php:7.4-fpm

ENV ACCEPT_EULA=Y
ENV PHP_UPLOAD_MAX_FILESIZE=1G
ENV PHP_MAX_INPUT_VARS=1G

COPY --chown=www-data:www-data . /usr/share/nginx/html
COPY .env.example /usr/share/nginx/html/.env

RUN apt-get update && apt-get install -y --allow-unauthenticated \
    gnupg \
    libcurl4-openssl-dev \
    libzip-dev \
    sendmail \
    libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install tokenizer \
    && docker-php-ext-install zip;

COPY ./docker/nginx/conf.d /etc/nginx/conf.d


RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

RUN cd ~ \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer;

RUN cd /usr/share/nginx/html \
    && composer install \
    && php artisan key:generate \
    && php artisan optimize:clear \
    && composer dump-autoload;

WORKDIR /usr/share/nginx/html/

EXPOSE 80

RUN chmod -R 0777 /usr/share/nginx/html/storage

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]
