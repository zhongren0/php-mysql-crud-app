FROM php:8.2-apache-alpine3.18

# Install build dependencies, then the PHP extension, then clean up
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS mariadb-connector-c-dev && \
    docker-php-ext-install mysqli && \
    apk del .build-deps

COPY . /var/www/html/
