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
COPY /docker/php/mail-send-consumer.conf    /etc/supervisor/conf.d/mail-send-consumer.conf
COPY /docker/php/mail-check-consumer.conf    /etc/supervisor/conf.d/mail-check-consumer.conf

CMD composer install && supervisord -c /etc/supervisor/supervisord.conf && supervisorctl reread && supervisorctl update && supervisorctl start manager-worker:* && php-fpm