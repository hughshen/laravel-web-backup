#!/bin/bash

CURRENTPATH=`pwd`
SCRIPTDIR=$(cd `dirname $0` && pwd)
DOCKERDIR=$(cd ${SCRIPTDIR}/../ && pwd)
PROJECTDIR=$(cd ${SCRIPTDIR}/../../ && pwd)

source ${PROJECTDIR}/.env

# MySQL
# Backup
docker exec ${DOCKER_MYSQL_NAME} /usr/bin/mysqldump --user=${DOCKER_MYSQL_USER} --password=${DOCKER_MYSQL_PASSWORD} ${DOCKER_MYSQL_DATABASE} > ${PROJECTDIR}/database/seeds/data.sql
# Restore
# cat data.sql | docker exec -i MYSQL_CONTAINER /usr/bin/mysql -u root --password=your_password DATABASE

# 0 3 * * * /path/to/backup.sh >/dev/null 2>&1
