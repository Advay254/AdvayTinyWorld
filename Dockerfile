FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

RUN a2enmod rewrite headers

COPY . /var/www/html/
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html/uploads \
    /var/www/html/data /var/www/html/logs

RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

EXPOSE 80
