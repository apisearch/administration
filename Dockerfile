FROM atoto/docker-nginx-php-stack
ENV DOCKER=true
ADD . /var/www/html
RUN composer install --no-dev --no-scripts --optimize-autoloader && \
    rm -rf temp/* log/* && \
    mv app/config/config.docker.neon app/config/config.local.neon && \
    chown -R www-data:www-data /var/www/* && \
    chmod -R 777 temp log
USER www-data
