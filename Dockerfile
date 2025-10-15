# PHP mới nhất (tag 'php:apache' luôn là bản stable/latest)
FROM php:apache

# Cài Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
    
# Cài extension cho MySQL (Eloquent cần)
RUN docker-php-ext-install pdo pdo_mysql

# Copy Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Bật mod_rewrite cho Apache
RUN a2enmod rewrite

# Cài libzip + zip extension
RUN apt-get update && apt-get install -y \
        zip \
        unzip \
        libzip-dev \
        zlib1g-dev \
    && docker-php-ext-install zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install redis \
    && docker-php-ext-enable redis

EXPOSE 80
