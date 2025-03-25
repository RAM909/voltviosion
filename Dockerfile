# Use the official PHP image with Apache
FROM php:8.2-apache

# Install the PostgreSQL driver for PHP
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copy your project files into the container
COPY . /var/www/html/

# Expose port 80 for Apache
EXPOSE 80
