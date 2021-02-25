## Bootstrap from PHP container
FROM php:7.4-apache

## Setting up default workdir
WORKDIR "/var/www/html"


## Add PHP Core Extensions, like GD Library, iconv, MySQLI, Gettext
RUN apt-get update && apt-get install -y \
	unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        graphviz 

RUN docker-php-ext-install mbstring \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd 
    #&& docker-php-ext-install ctype
    #&& docker-php-ext-install curl

COPY . .

#remove this, why?
RUN rm -rf /var/lib/apt/lists/*

## Enable modrewrite and SSL module
RUN a2enmod rewrite
RUN a2enmod ssl
EXPOSE 80

