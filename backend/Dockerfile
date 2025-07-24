FROM php:8.2-apache


# Update and install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    pkg-config \
    libssl-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb
# # Install Redis extension
# RUN pecl install redis && docker-php-ext-enable redis
# # Install GD extension
# RUN docker-php-ext-install gd

RUN docker-php-ext-install sockets

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod headers

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

RUN sed -i '/<VirtualHost \*:80>/a \
<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy your app code
COPY . .

# Permissions (optional, depends on your setup)
RUN chown -R www-data:www-data /var/www/html
