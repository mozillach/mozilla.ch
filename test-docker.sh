#! /bin/sh
set -e

CERT_FOLDER=.docker-certs
CONTAINER_NAME="mozillach/mozilla.ch"

mkdir -p $CERT_FOLDER

# Stop any existing container
RUNNING_CONTAINERS=$(docker ps -f ancestor=$CONTAINER_NAME -q)
if [ $RUNNING_CONTAINERS ] ; then
    docker stop $RUNNING_CONTAINERS
fi

# Let's fund a CA
if [ ! -f $CERT_FOLDER/ca.key ] ; then
openssl genrsa -des3 -passout pass:password -out $CERT_FOLDER/ca.key 2048
fi
if [ ! -f $CERT_FOLDER/ca.crt ] ; then
openssl req -new -passin pass:password \
        -subj '/CN=Non-Prod Test CA/C=US' \
        -x509 -days 365 -key $CERT_FOLDER/ca.key -out $CERT_FOLDER/ca.crt
fi

# Create certificate for "mozilla.ch"
if [ ! -f $CERT_FOLDER/server.key ] ; then
openssl genrsa -passout pass:password -des3 -out $CERT_FOLDER/server.key 2048
fi
if [ ! -f $CERT_FOLDER/server.csr ] ; then
openssl req -passin pass:password -subj '/CN=mozilla.ch' -new -key $CERT_FOLDER/server.key -out $CERT_FOLDER/server.csr
fi
if [ ! -f $CERT_FOLDER/server.crt ] ; then
openssl x509 -passin pass:password -req -days 365 -in $CERT_FOLDER/server.csr -CA $CERT_FOLDER/ca.crt -CAkey $CERT_FOLDER/ca.key -set_serial 01 -out $CERT_FOLDER/server.crt
fi
openssl rsa -passin pass:password -in $CERT_FOLDER/server.key -out $CERT_FOLDER/server.key

# Build and run the docker container on port 4430
docker build -t $CONTAINER_NAME .
docker run -p 4430:443 -d -h mozilla.ch -v $PWD/$CERT_FOLDER:/usr/local/apache2/conf $CONTAINER_NAME
