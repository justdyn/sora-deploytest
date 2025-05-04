#!/bin/bash

echo "Installing PHP dependencies..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "Installing Node.js dependencies..."
npm install

echo "Building assets..."
npm run build

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Build completed!" 