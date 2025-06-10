#!/bin/sh
set -e

php bin/console cache:clear
php bin/console doctrine:migrations:migrate --no-interaction || true

exec "$@"