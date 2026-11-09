FROM php:8.4-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libicu-dev libonig-dev libcurl4-openssl-dev libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring bcmath intl zip curl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/entrypoint.sh /usr/local/bin/inventaris-entrypoint
RUN chmod +x /usr/local/bin/inventaris-entrypoint \
    && sed -ri 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

WORKDIR /var/www/html
EXPOSE 80
ENTRYPOINT ["inventaris-entrypoint"]
