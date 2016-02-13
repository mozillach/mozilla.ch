#! /bin/sh

# Run composer scripts, since we have the ENV variable now
composer.phar run-script post-install-cmd

php app/console cache:clear --env=prod --no-debug --no-warmup

# Dump assetic assets
php app/console assetic:dump --env=prod --no-debug

apache2-foreground
