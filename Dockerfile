# =====================================================================
# Image applicative : PHP 8.3 + Apache pour l'app de suivi des colis
# =====================================================================
FROM php:8.3-apache

# Dependances systeme necessaires aux extensions PHP du projet
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip \
        libzip-dev \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libonig-dev \
        libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip mbstring curl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer (copie depuis l'image officielle)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache : la racine web est src/public (la ou se trouve index.php + .htaccess)
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# On copie d'abord composer.* pour profiter du cache de build
COPY src/composer.json src/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts || true

# Puis le reste de l'application
COPY src/ /var/www/html/

# Le .htaccess de src/ est un "deny all" prevu pour l'ancienne config ou
# Apache servait src/ directement. Ici la racine web est src/public, donc
# /var/www/html n'est jamais expose : ce fichier ne ferait que tout bloquer.
RUN rm -f /var/www/html/.htaccess

# Generation finale de l'autoloader (lib-tools/Helpers/helpers.php est present)
RUN composer dump-autoload --optimize --no-interaction \
    && chown -R www-data:www-data /var/www/html

# Script d'attente de la base avant de lancer Apache
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
