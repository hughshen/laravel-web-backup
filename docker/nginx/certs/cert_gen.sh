#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
DOCKERDIR=$(cd ${SCRIPTDIR}/../../ && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../../../ && pwd)

source ${PROJECTDIR}/.env

mv ${DOCKERDIR}/nginx/conf.d/default.conf ${DOCKERDIR}/nginx/conf.d/default.conf.bak
cp -n ${DOCKERDIR}/nginx/conf.d/default.https.conf.example ${DOCKERDIR}/nginx/conf.d/default.https.conf
sed -i -e "s/main_host/$DOCKER_HTTPS_MAIN_HOST/g" ${DOCKERDIR}/nginx/conf.d/default.https.conf
sed -i -e "s/main_server_name/$DOCKER_HTTPS_SERVER_NAME/g" ${DOCKERDIR}/nginx/conf.d/default.https.conf

cd ${SCRIPTDIR}/ssl

# Create a Let's Encrypt account private key
openssl genrsa 4096 > account.key
# Generate a domain private key
openssl genrsa 4096 > domain.key

openssl req -new -sha256 -key domain.key -subj "/" -reqexts SAN -config <(cat /etc/ssl/openssl.cnf <(printf "[SAN]\nsubjectAltName=$DOCKER_SSL_DNS")) > domain.csr

# Download the acme-tiny script
wget -O acme_tiny.py https://raw.githubusercontent.com/diafygi/acme-tiny/master/acme_tiny.py
# Get a signed certificate
python acme_tiny.py --account-key ./account.key --csr ./domain.csr --acme-dir ${SCRIPTDIR}/challenges/ > ./signed.crt
# Download the intermediate certificate
wget -O - https://letsencrypt.org/certs/lets-encrypt-x3-cross-signed.pem > intermediate.pem
# Merge signed.crt and intermediate.pem
cat signed.crt intermediate.pem > chained.pem
# Download the root certificate and merge the intermediate certificate for OCSP Stapling
wget -O - https://letsencrypt.org/certs/isrgrootx1.pem > root.pem
cat intermediate.pem root.pem > full_chained.pem

openssl dhparam -out dhparams.pem 2048

openssl rand 48 > session_ticket.key

docker restart ${DOCKER_NGINX_NAME}

cd $CURRENTPATH
exit 0