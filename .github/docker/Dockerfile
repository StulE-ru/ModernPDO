ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-alpine

ARG WORKDIR=/var/www/data

# Install xdebug pacakge
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

COPY .github/docker/php/docker.conf /usr/local/etc/php-fpm.d/docker.conf

COPY .github/docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

USER www-data

WORKDIR ${WORKDIR}

CMD ["php-fpm"]

EXPOSE 9000 9003
