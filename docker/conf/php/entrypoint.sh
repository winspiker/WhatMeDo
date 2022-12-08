#!/usr/bin/env bash
composer install;

php bin/console doctrine:migrations:migrate

php-fpm;