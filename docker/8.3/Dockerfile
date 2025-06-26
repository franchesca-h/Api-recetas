# docker/8.3/Dockerfile
ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-fpm-alpine

LABEL maintainer="Franchesca  <franchescahernandezv@outlook.com>"

# Argumentos configurables
ARG WWWGROUP=1000
ARG WWWUSER=1000
ARG XDEBUG_VERSION=3.3.1

WORKDIR /var/www/html
# 1. Instalar dependencias del sistema
RUN apk update && apk add --no-cache \
    bash \
    shadow \
    sudo \
    autoconf \
    make \
    g++ \
    git \
    curl \
    wget \
    libzip-dev \
    zip \
    unzip \

# PASO 1: Instalar dependencias del sistema operativo y herramientas básicas
RUN apk add --no-cache \
    autoconf \
    git \
    curl \
    build-base \
    linux-headers \
    # Librerías necesarias para extensiones PHP 
    #(Actualización de Dockerfile)
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    # HEAD
    freetype-dev \
    libxml2-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    pkgconf \
    re2c \
    linux-headers

# 2. Instalar extensiones PHP
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \  
    zip \
    exif \
    pcntl \
    intl \
    gd \
    opcache

# 3. Instalar Xdebug
RUN pecl install xdebug-${XDEBUG_VERSION} && \
    docker-php-ext-enable xdebug && \
    echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini

# 4. Configurar usuario
RUN apk add --no-cache shadow && \
    if ! getent group www-data >/dev/null; then addgroup -g ${WWWGROUP} www-data; fi && \
    if ! getent passwd www-data >/dev/null; then adduser -u ${WWWUSER} -G www-data -s /bin/sh -D www-data; fi && \
    mkdir -p /home/www-data && \
    chown -R www-data:www-data /var/www/html /home/www-data && \
    echo "www-data ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Instalar Node.js 18 LTS (solución definitiva)
RUN apk add --no-cache --virtual .build-deps \
    curl \
    && curl -fsSL https://unofficial-builds.nodejs.org/download/release/v18.19.1/node-v18.19.1-linux-x64-musl.tar.xz | tar -xJf - -C /usr --strip-components=1 \
    && apk del .build-deps \
    && npm install -g yarn

# 7. Limpieza
RUN apk del --purge autoconf make g++ && \
    rm -rf /var/cache/apk/* /tmp/* /var/tmp/* /usr/share/doc/*

USER www-data
EXPOSE 9000
CMD ["php-fpm"]

    postgresql-dev # Si usas PostgreSQL

# PASO 2: Instalar extensiones de PHP que no requieren PECL/compilación avanzada
RUN docker-php-ext-install \
    pdo pdo_mysql \
    zip exif pcntl opcache \
    intl \
    gd # gd requiere configuración, pero esta es una instalación básica

# PASO 3: Configurar GD con las opciones necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

# PASO 4: Instalar Xdebug (requiere autoconf y linux-headers)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=${XDEBUG_MODE:-off}" >> /etc/php/${PHP_VERSION}/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=${XDEBUG_CONFIG:-host.docker.internal}" >> /etc/php/${PHP_VERSION}/conf.d/docker-php-ext-xdebug.ini

# PASO 5: Configuración de usuario y grupo (mantener estas líneas)
RUN groupadd --gid $WWWGROUP sail
RUN useradd -ms /bin/bash --gid $WWWGROUP sail

# PASO 6: Instalar Node.js y npm (si los necesitas para front-end)
RUN curl -sL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apk add --no-cache nodejs npm \
    && npm install -g npm@latest

# PASO 7: Limpieza final
RUN rm -rf /var/cache/apk/* /tmp/* /var/tmp/* /usr/share/doc/*
