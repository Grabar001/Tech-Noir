#!/bin/sh
set -e

composer dump-autoload --optimize

exec "$@"