FROM php:8.2-fpm

# 1. Install library sistem yang sering dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 2. Install ekstensi PHP (resep wajib Laravel)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Ambil Composer (Manajer paket PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set folder kerja
WORKDIR /var/www
