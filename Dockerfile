FROM php:8.3-apache

# Extensión de MySQL + certificados (para conexiones SSL) + configuración de producción de PHP
RUN apt-get update \
 && apt-get install -y --no-install-recommends ca-certificates \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install pdo_mysql \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . /var/www/html/

# Deja en la web solo lo necesario; los scripts y el SQL quedan fuera de la carpeta pública
RUN mkdir -p /opt/app \
 && mv /var/www/html/docker/entrypoint.sh /var/www/html/docker/init_db.php /opt/app/ \
 && mv /var/www/html/docker/apache.conf /etc/apache2/conf-available/app.conf \
 && mv /var/www/html/database.sql /opt/app/ \
 && rm -rf /var/www/html/docker /var/www/html/migracion.sql /var/www/html/LEEME.txt \
           /var/www/html/README.md /var/www/html/Dockerfile /var/www/html/render.yaml \
 && sed -i 's/\r$//' /opt/app/entrypoint.sh /etc/apache2/conf-available/app.conf \
 && chmod +x /opt/app/entrypoint.sh \
 && a2enconf app \
 && chown -R www-data:www-data /var/www/html

EXPOSE 10000
CMD ["/opt/app/entrypoint.sh"]
