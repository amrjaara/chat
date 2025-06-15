Use PHP 8.2 with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP curl and system dependencies
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    && docker-php-ext-install curl

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY index.php .
COPY style.css .

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
