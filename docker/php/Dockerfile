FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip

RUN docker-php-ext-install pdo mysqli pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ../docker/apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf

COPY . /var/www/app

WORKDIR /var/www/app

CMD ["./docker/init.sh"]