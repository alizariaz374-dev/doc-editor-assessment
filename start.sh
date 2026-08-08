#!/usr/bin/env bash
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php-fpm