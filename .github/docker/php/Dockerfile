ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-alpine

ARG WORKDIR=/var/www

# Install xdebug package
RUN apk add --no-cache --update --virtual buildDeps \
        autoconf \
        make \
        gcc \
        musl-dev \
        linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del buildDeps

RUN docker-php-ext-install pdo_mysql

# Clear cache
RUN rm -rf /tmp/pear \
    && rm -rf /var/cache/apk/*

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY .github/docker/php/docker.conf /usr/local/etc/php-fpm.d/docker.conf

USER www-data

WORKDIR ${WORKDIR}

CMD ["php-fpm"]

EXPOSE 9000 9003
