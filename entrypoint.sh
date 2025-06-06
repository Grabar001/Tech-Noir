#!/bin/sh
set -e

# Проверка наличия .env
if [ ! -f ".env" ]; then
    echo ".env file not found, copying from .env.example"
    cp .env.example .env
fi

exec "$@"