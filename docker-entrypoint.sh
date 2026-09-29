#!/bin/sh
set -e

php artisan config:clear
php artisan migrate --force

PORT="${PORT:-10000}"
php artisan serve --host=0.0.0.0 --port="$PORT"
