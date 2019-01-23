FROM php:7.3-fpm-alpine

RUN apk add --no-cache composer

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/public"]
