#!/bin/sh
until echo '\q' | mysql --host=db --port=3306 --user=root --password= webshop; do
    >&2 echo "MySQL is unavailable - sleeping"
    sleep 1
done

php /app/yii migrate --interactive=0
docker-php-entrypoint apache2-foreground