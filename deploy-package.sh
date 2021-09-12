#!/bin/bash

tar -czf deploy.tar.gz \
  deploy.sh \
  docker-compose.prod.yml \
  docker-compose.yml