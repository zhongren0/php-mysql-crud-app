# Use PHP 8.2 with Apache server as the base image 

FROM php:8.2-apache 

 

# Install mysqli extension 

RUN docker-php-ext-install mysqli 

 

# Copy PHP app into Apache document root 

COPY . /var/www/html/ 
