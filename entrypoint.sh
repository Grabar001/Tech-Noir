


until pg_isready -h database -U app; do
  echo "Ждём базу данных..."
  sleep 2
done


php bin/console doctrine:migrations:migrate --no-interaction


exec "$@"