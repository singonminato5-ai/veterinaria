#!/bin/sh
set -e

# Render indica el puerto en la variable PORT (por defecto 10000)
PORT="${PORT:-10000}"
echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -Ei "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Crea las tablas y los datos iniciales solo si la base está vacía
php /opt/app/init_db.php || echo "[entrypoint] No se pudo inicializar la base de datos; revisa las variables DB_*. Se arranca igualmente."

exec apache2-foreground
