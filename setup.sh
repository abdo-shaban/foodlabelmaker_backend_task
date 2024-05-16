#!/bin/bash

# Run composer install
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# Copy .env.example to .env
cp .env.example .env

# Generate JWT secret
./vendor/bin/sail artisan jwt:secret

# Generate application key
./vendor/bin/sail artisan key:generate --ansi

# Start Sail containers
./vendor/bin/sail up -d

# Migrate and seed the database
./vendor/bin/sail artisan migrate --seed

# run tests
./vendor/bin/sail test
