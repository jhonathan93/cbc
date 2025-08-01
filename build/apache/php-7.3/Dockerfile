FROM php:7.3-apache

# Instale as dependências do sistema
RUN apt-get update && apt-get install -y \
    cron \
    default-mysql-client \
    git \
    gnupg \
    gzip \
    wget \
    curl \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libmcrypt-dev \
    libonig-dev \
    libpng-dev \
    libsodium-dev \
    libssh2-1-dev \
    libwebp-dev \
    libxslt1-dev \
    libzip-dev \
    lsof \
    imagemagick \
    mailutils \
    msmtp \
    procps \
    vim \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Habilitação do mod_rewrite e mod_headers
RUN a2enmod rewrite headers

# Configuração do document root para o diretório public do Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configura e instala extensões do PHP
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install \
    bcmath \
    bz2 \
    calendar \
    exif \
    gd \
    gettext \
    intl \
    mbstring \
    mysqli \
    opcache \
    pcntl \
    pdo_mysql \
    soap \
    sockets \
    sodium \
    sysvmsg \
    sysvsem \
    sysvshm \
    xsl \
    zip

# Instala o Xdebug
RUN pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug

# Instale o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configuring system
COPY .docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/config/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY .docker/config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY .docker/script/colors.sh /usr/local/bin/colors.sh
COPY .docker/script/signature.sh /usr/local/bin/signature.sh

# Configurações de permissão e usuário
RUN chown -R www-data:www-data /var/www \
    && usermod -u 1000 www-data \
    && chsh -s /bin/bash www-data \
    && chmod +x /usr/local/bin/colors.sh \
    && chmod +x /usr/local/bin/signature.sh

# Defina o volume e o diretório de trabalho
VOLUME /var/www/html
WORKDIR /var/www/html

EXPOSE 80