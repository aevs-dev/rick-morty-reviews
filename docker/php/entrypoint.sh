#!/bin/bash

set -e

until pg_isready -h pg -U ${DATABASE_USER} -d ${DATABASE_NAME}; do
  echo "Waiting for PostgreSQL..."
  sleep 1
done

composer install
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

exec "$@"