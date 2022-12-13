FROM php:8.1-fpm-alpine

ARG TIMEZONE="UTC"

ARG UID=1000
ARG GID=1000

ENV USERNAME=backend

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
    php81-pdo \
    php81-fileinfo

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
    shadow \
    sudo

RUN apk update && \
    apk add --no-cache -q \
        ${PERMANENT_DEPS} \
        ${PHPIZE_DEPS} && \
    docker-php-ext-configure pdo_pgsql --with-pdo-pgsql && \
    docker-php-ext-install pdo_pgsql && \
        rm -rf /var/cache/apk/* && \
        groupadd --gid ${GID} ${USERNAME} && \
        useradd -m ${USERNAME} --uid=${UID} --gid=${GID} -G ${USERNAME} && \
    echo "backend ALL=(root) NOPASSWD:ALL" > /etc/sudoers.d/backend && \
        chmod 0440 /etc/sudoers.d/backend

WORKDIR /app