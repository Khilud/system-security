# Use official PHP + Apache image
FROM php:8.2-apache

# Enable common PHP extensions (e.g. PDO + MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copy your app into the container
WORKDIR /var/www/html
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Optional: enable Apache rewrite if you use clean URLs
RUN a2enmod rewrite

# Expose Apache port
EXPOSE 80

# When running in Docker, your PHP code can read the AES key
# from /run/secrets/aes_master_key (we’ll map it later with docker run or docker-compose)
