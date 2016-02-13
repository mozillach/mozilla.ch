#! /bin/sh

# Run composer scripts, since we have the ENV variable now
composer.phar run-script post-install-cmd

php app/console cache:clear --env=prod --no-debug

# Dump assetic assets
php app/console assetic:dump --env=prod --no-debug

# Sets permissions
chown -R www-data:www-data app
chmod -R a+rw app/cache app/logs

apache2-foreground
