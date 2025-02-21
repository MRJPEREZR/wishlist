FROM bitnami/symfony:latest

WORKDIR /app

COPY . /app

# Ensure dependencies are installed
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Doctrine ORM explicitly
RUN composer require symfony/orm-pack --no-scripts --no-interactio

EXPOSE 8000
