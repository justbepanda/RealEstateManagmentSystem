FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

RUN groupadd -g ${GID} app \
    && useradd -m -u ${UID} -g ${GID} app

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    libpq-dev \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_pgsql mbstring exif bcmath gd intl zip pcntl

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN chown -R app:app /var/www

RUN echo "upload_max_filesize=100M\npost_max_size=100M" > /usr/local/etc/php/conf.d/uploads.ini

USER app

COPY --chown=app:app . .

EXPOSE 9000
