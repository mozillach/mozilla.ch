#! /bin/sh

# Run composer scripts, since we have the ENV variable now
composer.phar run-script

# Warm up cache
php app/console cache:warmup --env=prod --no-debug

# Dump assetic assets
php app/console assetic:dump --env=prod --no-debug

apache2-foreground
