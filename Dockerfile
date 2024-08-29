FROM php:8.3.7RC1-fpm

WORKDIR /var/www/html

RUN pecl install -o -f redis &&  rm -rf /tmp/pear &&  docker-php-ext-enable redis
RUN printf 'upload_max_filesize=300M;\npost_max_size=300M;\nmax_execution_time=600;\nmax_input_time=600;\n' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

# Install system dependencies:
RUN apt-get update -y
RUN apt-get install -y \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev \
    ffmpeg \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer:
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY database /var/www/database
COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/

RUN composer install --no-scripts

COPY . /var/www/html

RUN chown -R www-data:www-data \
        /var/www/html/public \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache
