FROM php:7.4-apache

# Enable required Apache modules
RUN a2enmod rewrite ssl headers

# Install required system libraries and tools
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev libxml2-dev \
    libcurl4-openssl-dev libmariadb-dev libssl-dev libbz2-dev gettext \
    libxslt1-dev net-tools proftpd gnupg2 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    mysqli pdo_mysql gd mbstring exif bcmath zip curl xml bz2 gettext sockets xsl \
    && docker-php-ext-enable opcache

RUN apt-get update && apt-get install -y inetutils-ping telnet netcat default-mysql-client


# Copy application files
COPY app/ /var/www/html/

# Copy Apache configuration for SSL
COPY apache-conf/ssl.conf /etc/apache2/sites-available/ssl.conf

# Update dir.conf to include the required DirectoryIndex configuration
RUN rm -f /etc/apache2/conf-available/docker-php.conf && \
echo '<IfModule mod_dir.c>\n    DirectoryIndex index.php admin.php\n</IfModule>' > /etc/apache2/mods-available/dir.conf

# Enable SSL module and site
RUN a2enmod ssl && a2ensite ssl.conf

# Enable PHP error logging
RUN echo "log_errors = On" >> /usr/local/etc/php/php.ini && \
    echo "error_log = /var/log/php_errors.log" >> /usr/local/etc/php/php.ini

# Set permissions for the application
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html

# Expose necessary ports
EXPOSE 80 443
