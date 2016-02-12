#!/bin/sh
set -e

echo "env[SMTP_USER]='$SMTP_USER'" >> /etc/php/php-fpm.conf
echo "env[SMTP_PASS]='$SMTP_PASS'" >> /etc/php/php-fpm.conf
echo "env[SCRIPT_FILENAME] = /app/index.php" >> /etc/php/php-fpm.conf
echo "env[SCRIPT_NAME] = /app/index.php" >> /etc/php/php-fpm.conf

exec "$@"
