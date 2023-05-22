FROM php:7.4-apache

# Copy PHP files into container
COPY . /Li3

# Set working directory
WORKDIR /Li3

# Install PHP extensions required by your application
RUN docker-php-ext-install pdo_mysql

# Expose port 80
EXPOSE 80