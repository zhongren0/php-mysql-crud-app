FROM php:8.2-apache-alpine

# Alpine Linux uses 'apk' for its package manager.
# We need to install the development headers for the MariaDB/MySQL connector
# before we can install the 'mysqli' PHP extension.
# We install them as "virtual" build dependencies so we can remove them later
# to keep the final image small.
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS mariadb-connector-c-dev && \
    docker-php-ext-install mysqli && \
    apk del .build-deps

# Copy the application source code into the Apache web root
COPY . /var/www/html/
