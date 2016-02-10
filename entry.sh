#!/bin/sh
set -e

echo "env[HOST]=$HOST" >> /etc/php/php-fpm.conf
echo "env[USER]=$USER" >> /etc/php/php-fpm.conf
echo "env[PASSWORD]=$PASSWORD" >> /etc/php/php-fpm.conf

exec "$@"
