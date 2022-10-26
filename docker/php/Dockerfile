FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libpq-dev \
        git \
        cron \
        vim \
        libxml2-dev \
        libfontconfig-dev \
        libxslt-dev \
        libxrender-dev \
        libzip-dev \
        zip \
        htop  \
        supervisor \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) iconv gd mysqli pdo_pgsql opcache bcmath zip sockets soap xsl intl \
    && docker-php-ext-enable mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY /docker/php/manager-worker.conf    /etc/supervisor/conf.d/manager-worker.conf
COPY /docker/php/consumer-worker.conf    /etc/supervisor/conf.d/consumer-worker.conf
COPY /docker/php/prepare.sh /usr/local/bin/prepare.sh

RUN chmod a+x /usr/local/bin/prepare.sh

CMD composer install && supervisord -c /etc/supervisor/supervisord.conf && supervisorctl reread && supervisorctl update && php-fpm