FROM php:7.2-fpm
MAINTAINER Adil YASSINE <adilyassine.info>

RUN apt-get update && apt-get install -y  zlib1g-dev libicu-dev libpq-dev imagemagick git \
	&& docker-php-ext-install opcache \
	&& docker-php-ext-install intl \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
	&& php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
	&& chmod +sx /usr/local/bin/composer

RUN echo 'export $(cat /var/www/todolist/.env | grep -v ^# | xargs)' >> /root/.bashrc

EXPOSE 9000
