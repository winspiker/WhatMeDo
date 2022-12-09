FROM php:8.1-fpm-alpine

ARG TIMEZONE="UTC"

ARG UID=1000
ARG GID=1000

ENV USER backend

ENV PS1='\[\033[1;32m\]🐳  \[\033[1;36m\][\u@\h] \[\033[1;34m\]\w\[\033[0;35m\] \[\033[1;36m\]# \[\033[0m\]'

# persistent / runtime deps
ENV PHPIZE_DEPS \
    php81-iconv \
    php81-ctype \
    php81-session \
    php81-simplexml \
    php81-tokenizer \
    php81-xml \
    php81-xmlwriter \
    php81-dom \
    php81-intl \
    php81-pdo

# permanent deps
ENV PERMANENT_DEPS \
    bash \
    git \
    zip \
    unzip \
    autoconf \
    g++ \
    make \
    linux-headers \
    postgresql-dev \
    composer \
    nodejs \
    npm \
    shadow

COPY ./docker/conf/php/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/conf/php/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY docker/conf/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/conf/php/php.ini /usr/local/etc/php/conf.d/php.ini

RUN apk update && \
    apk add --no-cache -q \
        ${PERMANENT_DEPS} \
        ${PHPIZE_DEPS} && \
    pecl install xdebug-3.1.6 && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-configure pdo_pgsql --with-pdo-pgsql && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache && \
    rm -rf /var/cache/apk/* && \
    useradd -m ${USER} --uid=${UID} && \
    chmod +x /usr/local/bin/entrypoint.sh

# set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && \
    echo ${TIMEZONE} > /etc/timezone && \
    printf '[PHP]\ndate.timezone = "%s"\n', "$TIMEZONE" > \
    /usr/local/etc/php/conf.d/tzone.ini && "date"


WORKDIR /app

USER ${USER}

ENTRYPOINT /usr/local/bin/entrypoint.sh