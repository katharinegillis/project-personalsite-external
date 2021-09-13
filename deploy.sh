#!/bin/bash

# Determine which colour is staging
if grep -q 'set \$backend green:8080;' ../.docker/ingress/conf.d/default.conf
then
    STAGING="blue"
else
    STAGING="green"
fi

UID=$(id -u)
GID=$(id -g)

sed -i "s/UID=1000/UID=$UID/g" .env
sed -i "s/GID=1000/GID=$GID/g" .env
sed -i "s/SITE_URL=externals.katiecordescodes.docker/SITE_URL=$SITE_URL/g" .env
sed -i "s/APP_ENV=dev/APP_ENV=prod/g" .env
sed -i "s/AAPP_SECRET=\$ecretf0rt3st/APP_SECRET=$APP_SECRET/g" .env
{
  echo ""
  echo "COMPOSE_PROJECT_NAME=personalsite-externals-$STAGING"
} >> .env

# Update staging
docker-compose -f docker-compose.yml -f docker-compose.prod.yml -f "../docker-compose.$STAGING.yml" pull
docker-compose -f docker-compose.yml -f docker-compose.prod.yml -f "../docker-compose.$STAGING.yml" down
docker-compose -f docker-compose.yml -f docker-compose.prod.yml -f "../docker-compose.$STAGING.yml" up -d --remove-orphans