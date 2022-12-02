FROM alpine:3.17

ARG user
ARG uid

RUN apk update && \
    apk add --no-cache -q \
    bash \
    git \
    zip \
    unzip \
    postgresql-dev \
    php81 \
    php81-fpm \
    php81-iconv \
    php81-ctype \
    php81-session \
    php81-simplexml \
    php81-tokenizer \
    php81-pdo \
    php81-xml \
    php81-xmlwriter \
    php81-dom \
    php81-pdo \
    composer \
    shadow

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user --badname $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /app

# Set the user
USER $user