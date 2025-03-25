# Use the official PHP image with Apache
FROM php:8.2-apache

# Install the PostgreSQL driver for PHP and other dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite

# Set the document root to the "public" directory
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Copy the application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod +x /var/www/html/deploy.sh

# Expose port 80
EXPOSE 80

# Create a startup script
COPY ./startup.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/startup.sh

# Start Apache with our custom startup script
CMD ["/usr/local/bin/startup.sh"]
