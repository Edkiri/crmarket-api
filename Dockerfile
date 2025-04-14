FROM php:8.1-apache

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    git \
    nano \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    zlib1g-dev \
    libzip-dev \
    nodejs \
    npm \
    libmagickwand-dev; \
    pecl install imagick;

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install channel://pecl.php.net/xmlrpc-1.0.0RC3 xmlrpc

RUN docker-php-ext-enable xmlrpc imagick

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-ram-limit.ini

RUN a2enmod rewrite

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/html

USER root

COPY apache/default.conf /etc/apache2/sites-enabled/000-default.conf

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

USER $user
