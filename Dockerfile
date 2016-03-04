FROM php:5.5.32-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable pdo pdo_mysql mysqli
RUN a2enmod rewrite
COPY docker/php/000-default.conf /etc/apache2/sites-enabled/
RUN rm -rf /usr/src/php/ && \
		rm -rf /usr/share/doc/