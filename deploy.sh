#!/bin/bash

# Determine which colour is staging
if grep -q 'set \$backend green:8080;' ../.docker/ingress/conf.d/default.conf
then
    STAGING="green"
else
    STAGING="blue"
fi

# Recreate the .env file
rm .env
touch .env
{
  echo TRAEFIK_NAME="personalsite-externals"
  echo SITE_URL="${SITE_URL}"
} >> .env

# Update staging
docker-compose -f docker-compose.yml -f docker-compose.prod.yml -f "../docker-compose.$STAGING.yml" pull
docker-compose -f docker-compose.yml -f docker-compose.prod.yml -f "../docker-compose.$STAGING.yml" down
docker-compose -f docker-compose.yml -f docker-compose.prod.yml -f "../docker-compose.$STAGING.yml" up -d --remove-orphans