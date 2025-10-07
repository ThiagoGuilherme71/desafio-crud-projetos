#!/bin/sh

# Usa o ID 1000 como fallback
TARGET_UID=${DOCKER_USER_ID:-1000}
TARGET_GID=${DOCKER_GROUP_ID:-1000}

echo "Ajustando as permissões de arquivos para UID: $TARGET_UID GID: $TARGET_GID"
chown -R $TARGET_UID:$TARGET_GID /var/www/html
chmod -R ug+w /var/www/html/storage
chmod -R ug+w /var/www/html/bootstrap/cache

# Executa o comando principal (php-fpm)
exec "$@"
