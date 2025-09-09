FROM php:8.2-apache

# Copie tous les fichiers dans le dossier web
COPY . /var/www/html/

# Active le module rewrite d'Apache
RUN a2enmod rewrite

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
