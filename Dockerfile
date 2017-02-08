FROM atoto/docker-nginx-php-stack
ENV DOCKER=true
ADD . /var/www/html
RUN composer install --no-dev --no-scripts --optimize-autoloader && \
    rm -rf temp/* log/* && \
    mv app/config/config.docker.neon app/config/config.local.neon && \
    mv web/index.php web/app.php && \
    chown -R www-data:www-data /var/www/* && \
    chmod -R 777 temp log
USER www-data
