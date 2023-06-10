#syntax=docker/dockerfile:1.4

FROM php:8.1-cli-alpine

ENV XDEBUG_VERSION 3.2.0

RUN \
    #build-deps
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS autoconf git zlib-dev\
    #xdebug
    && apk add --no-cache linux-headers bash coreutils \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-install xdebug \
    #opcache
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache \
    #clear
    && apk del .build-deps \
    && docker-php-source delete

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY --link ./conf.d $PHP_INI_DIR/conf.d

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

ARG UID
ARG GID
RUN addgroup -g ${GID} -S app && adduser --uid ${UID} --ingroup app -S -g app app

WORKDIR /app

USER app
