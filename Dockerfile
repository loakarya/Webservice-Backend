FROM php:8.0-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install supervisor for maintaining laravel queue worker process
RUN apt-get update && apt-get install -y \
    supervisor \
    nano \
    sudo

RUN usermod -aG sudo $user
RUN echo "$user ALL=(ALL) NOPASSWD: /usr/bin/supervisord" >> /etc/sudoers

# CMD ["sudo", "supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]

# Set working directory
WORKDIR /var/www

USER $user

