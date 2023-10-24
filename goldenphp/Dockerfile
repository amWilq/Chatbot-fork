# Use the official PHP 8.2 image as the base image
FROM phpswoole/swoole:php8.2

# Set environment variables for configuration
ENV APP_ENV=dev \
    APP_DEBUG=1 \
    SWOOLE_RUNTIME=1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    SWOOLE_VERSION=5.1.0 \
    XDEBUG_VERSION=3.2.2

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
#RUN apt-get update && apt-get install -y supervisor
#RUN echo "[supervisord]\npidfile=/run/supervisord.pid\nuser=root\n" >  /etc/supervisord.conf
#RUN echo "[supervisorctl]\n" >> /etc/supervisord.conf
#RUN echo "[include]\nfiles = /etc/supervisor.d/*.conf\n" >> /etc/supervisord.conf
COPY supervisor.d /etc/supervisor/service.d

# Set the working directory
WORKDIR /var/www

# Entrypoint
#COPY start-container /usr/local/bin/start-container

# Expose the necessary ports
EXPOSE 80

#RUN chmod +x /usr/local/bin/start-container

#ENTRYPOINT ["start-container"]
