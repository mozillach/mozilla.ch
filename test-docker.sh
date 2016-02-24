#! /bin/sh
set -e

mkdir -p .docker-certs

# Let's fund a CA
openssl genrsa -des3 -passout pass:password -out .docker-certs/ca.key 2048
openssl req -new -passin pass:password \
        -subj '/CN=Non-Prod Test CA/C=US' \
        -x509 -days 365 -key .docker-certs/ca.key -out .docker-certs/ca.crt

# Create certificate for "mozilla.ch"
openssl genrsa -passout pass:password -des3 -out .docker-certs/server.key 2048
openssl req -passin pass:password -subj '/CN=mozilla.ch' -new -key .docker-certs/server.key -out .docker-certs/server.csr
openssl x509 -passin pass:password -req -days 365 -in .docker-certs/server.csr -CA .docker-certs/ca.crt -CAkey .docker-certs/ca.key -set_serial 01 -out .docker-certs/server.crt
openssl rsa -passin pass:password -in .docker-certs/server.key -out .docker-certs/server.key

# Build and run the docker container on port 4430
docker build -t mozillach/mozilla.ch .
docker run -p 4430:443 -d -h mozilla.ch -v $PWD/.docker-certs:/usr/local/apache2/conf mozillach/mozilla.ch
