FROM php:8.2-fpm

WORKDIR /var/www

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Copy existing application files to container
COPY ./web_app /var/www

# Install Symfony dependencies
RUN composer install --no-scripts --no-autoloader && composer dump-autoload

# Set permissions
RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]