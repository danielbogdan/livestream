FROM php:7.4-apache

# Enable required PHP modules
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Docker CLI
RUN apt-get update && apt-get install -y \
    docker.io \
    curl \
    git \
    net-tools \
    netcat-openbsd \
    iputils-ping \
    procps \
    vim \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY ./ /var/www/html/

# Update Apache configuration to listen on port 8000
RUN echo "Listen 8000" >> /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8000>/' /etc/apache2/sites-enabled/000-default.conf

# Add the <FilesMatch \.php$> block to the Apache configuration
RUN sed -i '/DocumentRoot \/var\/www\/html/a \
<FilesMatch \\.php$>\n\
    SetHandler application/x-httpd-php\n\
</FilesMatch>' /etc/apache2/sites-enabled/000-default.conf

# Expose port 8000
EXPOSE 8000

#RUN groupadd -g $(stat -c '%g' /var/run/docker.sock) docker && \
#    usermod -aG docker root && chmod 666 /var/run/docker.sock

# Restart Apache to apply the configuration
CMD ["apache2-foreground"]
