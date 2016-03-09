FROM php:7.0-apache

ENV SYMFONY_ENV=prod
ENV BEHIND_PROXY=0

RUN apt-get update -q && apt-get install -yq git libicu-dev zlib1g-dev libicu52 zlib1g --no-install-recommends

# PHP config
RUN docker-php-ext-install intl mbstring zip opcache

# Clean up packages
RUN apt-get purge -y --auto-remove libicu-dev zlib1g-dev && \
    rm -rf /var/lib/apt/lists/*

# Apache config
RUN a2enmod rewrite
RUN a2enmod headers

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin

# Copy in only composer relevant files, so the composer step is only re-run when those change
COPY composer.json composer.lock ./

# Install vendor deps
RUN composer.phar install --no-dev --optimize-autoloader --no-scripts

COPY mozillach.conf /etc/apache2/sites-enabled/mozillach.conf

# Copy in the website
COPY . .
RUN rm start.sh mozillach.conf

# Open ports
EXPOSE 80

# Run stuff
COPY start.sh /opt/start.sh
CMD [ "/opt/start.sh" ]
