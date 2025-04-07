# Use official PHP image as the base
FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zlib1g-dev libxml2-dev git unzip netcat-openbsd && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql xml && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apt-get clean

# Enable Xdebug for coverage
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name 'xdebug.so')" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=no" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Set the working directory
WORKDIR /var/www

# Copy the composer file
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Laravel application into the container
COPY . .

# Make executable entrypoint.sh
RUN chmod +x /var/www/entrypoint.sh

# Expose port 9000 to access the backend API
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
