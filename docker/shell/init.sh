#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
DOCKERDIR=$(cd ${SCRIPTDIR}/../ && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../../ && pwd)

# echo $PROJECTDIR
cd $PROJECTDIR

# ENV
cp -n ${PROJECTDIR}/.env.example ${PROJECTDIR}/.env
source ${PROJECTDIR}/.env

# Nginx config
cp -n ${DOCKERDIR}/nginx/conf.d/default.conf.example ${DOCKERDIR}/nginx/conf.d/default.conf
sed -i -e "s/main_server_name/$DOCKER_DEFAULT_SERVER_NAME/g" ${DOCKERDIR}/nginx/conf.d/default.conf

# PHP composer
docker pull composer

# Install packages and optimizing
docker run --rm --interactive --tty \
    --volume $PROJECTDIR:/app \
    --volume $PROJECTDIR/.composer:/tmp \
    composer install --optimize-autoloader

# Laravel setting
chmod 777 $(find ${PROJECTDIR}/storage -type d)

# Docker build
docker-compose up -d
# docker-compose build

cd $CURRENTPATH
exit 0
