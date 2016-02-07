#! /bin/sh

# Install vendor deps
composer.phar install --no-dev --optimize-autoloader

# Run symfony server checks
php app/check.php

# Warm up cache
php app/console cache:warmup --env=prod --no-debug

# Dump assetic assets
php app/console assetic:dump --env=prod --no-debug

# Make cache dir writeable n stuff
chown -R www-data:www-data app
chmod -R a+rw app/cache app/logs

apache2-foreground
