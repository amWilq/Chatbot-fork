# Use the official PHP 8.2 image as the base image
FROM phpswoole/swoole:php8.2

ARG APP_ENV
ARG APP_DEBUG
ARG SWOOLE_VERSION
ARG XDEBUG_VERSION

# Set environment variables for configuration
ENV APP_ENV=${APP_ENV} \
    APP_DEBUG=${APP_DEBUG} \
    SWOOLE_RUNTIME=1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    SWOOLE_VERSION=${SWOOLE_VERSION} \
    XDEBUG_VERSION=${XDEBUG_VERSION}

# Install dev dependencies
RUN apt-get update && apt-get install -y \
    autoconf \
    curl \
    g++ \
    gcc \
    git \
    imagemagick \
    libc6-dev \
    libcurl4-gnutls-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libmemcached-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libsqlite3-dev \
    libtool \
    libxml2-dev \
    libzip-dev \
    make \
    zlib1g-dev

# Install XDebug extension
RUN pecl install xdebug-$XDEBUG_VERSION \
    && docker-php-ext-enable xdebug

RUN pecl install memcached \
    && docker-php-ext-enable memcached


# Install PHP extensions
RUN docker-php-ext-install \
    bcmath \
    calendar \
    curl \
    exif \
    gd \
    intl \
    mbstring \
    pcntl \
    soap \
    xml \
    zip

# Cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get update && apt-get install -y symfony-cli

# Supervisor
COPY supervisor.d /etc/supervisor/service.d

# Start the application
RUN enable-supervisord-program.sh app-swoole

# Set the working directory
WORKDIR /var/www

# Expose the necessary ports
EXPOSE 80
