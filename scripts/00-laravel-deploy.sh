#!/usr/bin/env bash
set -e

echo "Running Laravel deployment..."

composer install --no-dev --optimize-autoloader

php artisan config:clear

echo "Running database migrations..."
php artisan migrate --force

echo "Creating storage link..."
php artisan storage:link || true

echo "Caching Laravel configuration..."
php artisan config:cache

echo "Caching views..."
php artisan view:cache

echo "Laravel deployment complete."