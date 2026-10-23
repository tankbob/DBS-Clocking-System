FROM php:7.4-apache AS build

RUN apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        zlib1g-dev \
        libpng-dev \
        libwebp-dev \
        libjpeg62-turbo-dev \
        libpng-dev libxpm-dev \
        libfreetype6-dev \
	&& docker-php-ext-configure gd \
	    --with-gd \
	    --with-webp-dir \
	    --with-jpeg-dir \
	    --with-png-dir \
	    --with-zlib-dir \
	    --with-xpm-dir \
	    --with-freetype-dir \
	    --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-configure zip --with-libzip \
	&& docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql bcmath zip\
        && docker-php-ext-enable mysqli

RUN a2enmod rewrite
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/conf.d/dev.ini
RUN echo "PassEnv DB_HOST DB_USERNAME DB_DATABASE DB_PASSWORD APP_ENV APP_DEBUG APP_KEY" >/etc/apache2/conf-available/lav-env.conf
WORKDIR /etc/apache2/conf-enabled
RUN ln -s ../conf-available/lav-env.conf lav-env.conf
RUN echo 'ServerName localhost;' >>/etc/apache2/apache2.conf

WORKDIR /src
COPY . .
RUN mv public html
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN groupadd -r user && useradd -r -g user user
RUN chown -R user: .
USER user
RUN composer install
RUN cp -f .env.example .env
#RUN php artisan key:generate
USER root
RUN mv -f /src/* /var/www
RUN mv -f /src/.env /var/www
RUN chown -R www-data: /var/www/*
