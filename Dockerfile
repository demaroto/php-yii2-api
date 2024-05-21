FROM php:8.2-cli as builder

WORKDIR /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');"

RUN apt-get update ; \
    apt-get install -y zip ;


COPY . /var/www/html/

RUN php composer.phar -n install

FROM php:8.2-fpm-alpine
# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www/html
COPY --from=builder /var/www/html .
RUN chown -R www-data:www-data /var/www/html
EXPOSE 9000
CMD [ "php-fpm" ]
