FROM php:8.0-apache

USER root

WORKDIR /var/www/html

RUN apt update && apt install -y \
        supervisor \
        nodejs \
        npm \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf
#COPY --from=composer /usr/bin/composer /usr/bin/composer
#RUN composer install
RUN chown -R www-data:www-data /var/www/html && a2enmod rewrite

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
#CMD ["sh", "-c", "composer install && /usr/bin/supervisord"]
CMD ["/usr/bin/supervisord"]

