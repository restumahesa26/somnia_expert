FROM php:8.2-fpm

# 1. Install Library Sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 2. Install Ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set Folder Kerja (PENTING)
WORKDIR /var/www

# ========================================================
# 5. COPY KODINGAN (BAGIAN INI YANG KEMUNGKINAN HILANG)
# ========================================================
COPY . .

# 6. Install Dependency Laravel (Agar vendor folder terbentuk)
RUN composer install --no-dev --optimize-autoloader

# 7. Beri Hak Akses ke folder storage (PENTING)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
