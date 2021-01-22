FROM php:7.3-cli
RUN pecl install curl json \
    && docker-php-ext-enable curl json
ADD / .
WORKDIR /
CMD [ "php", "./index.php" ]