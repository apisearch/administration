FROM atoto/docker-nginx-php-stack
ADD . /var/www/html
RUN composer install --no-dev --no-scripts --optimize-autoloader && \
    rm -rf temp/* log/* && \
    mv www/index.php www/app.php && \
    mv www web && \
    mv app/config/config.docker.neon app/config/config.local.neon && \
    chown -R www-data:www-data /var/www/* && \
    chmod -R 777 temp log && \
USER www-data
