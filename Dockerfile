FROM php:8.2-apache

# Install SQLite and other dependencies
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Create necessary directories and set permissions
RUN mkdir -p data uploads && \
    chmod 755 data uploads && \
    chown -R www-data:www-data data uploads

# Create .env if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
