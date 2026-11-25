FROM php:8.2-apache
RUN apt-get update && apt-get install -y mariadb-server \
    && docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    DirectoryIndex index.php index.html\n\
</Directory>' > /etc/apache2/conf-available/custom-php.conf \
    && a2enconf custom-php

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN service mariadb start \
    && mysql -e "CREATE DATABASE IF NOT EXISTS veterinario;" \
    && mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD(''); FLUSH PRIVILEGES;" \
    && mysql veterinario < /var/www/html/veterinario.sql

CMD service mariadb start && apache2-foreground
EXPOSE 80
