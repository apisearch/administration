FROM atoto/docker-nginx-php-stack
ADD . /var/www/html
RUN composer install --no-dev --no-scripts --optimize-autoloader && \
    rm -rf temp/* log/* && \
    chmod -R 777 temp log
USER www-data
