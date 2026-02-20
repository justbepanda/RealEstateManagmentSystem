FROM php:8.4-cli

ARG UID=1000
ARG GID=1000

# Создаём системного пользователя
RUN groupadd -g ${GID} app \
    && useradd -m -u ${UID} -g ${GID} app

# Устанавливаем системные зависимости
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

# PHP расширения (добавлено intl для Orchid и zip для Composer)
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd sockets intl zip

# Redis
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# RoadRunner
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /usr/bin/rr

WORKDIR /var/www

# Права на директории до копирования (оптимизация слоев)
RUN chown -R app:app /var/www

USER app

# Копируем файлы проекта (убедись, что .dockerignore настроен)
COPY --chown=app:app . .

EXPOSE 8000

CMD ["supervisord", "-c", "/var/www/supervisord.conf"]
