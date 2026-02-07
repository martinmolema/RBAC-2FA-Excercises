FROM php:8.2-apache-bullseye

COPY ini/php.ini /usr/local/etc/php
COPY ini/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY --chown=root:root conf/*.conf /etc/apache2/sites-available

RUN apt update && apt-get install libldap2-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install pdo pdo_mysql ldap \
    && a2enmod rewrite \
    && a2enmod headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Disable default site and enable our own
RUN a2dissite 000-default default-ssl \
    && rm /etc/apache2/sites-available/000-default.conf  /etc/apache2/sites-available/default-ssl.conf \
    && a2ensite grades.conf sharepoint.conf admin.conf marketing.conf hrm.conf portal.conf \
    && a2enmod ldap authnz_ldap

