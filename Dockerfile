# Etape 1 : Construire l'environnement d'application

# Utiliser une image de base PHP avec Composer intégré
FROM php:8.1-fpm

# Installe les dépendances système
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    npm \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Installe Composer (gestionnaire de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application Laravel dans le conteneur
WORKDIR /var/www
COPY . /var/www

# Installer les dépendances de Laravel
RUN composer install

# Donner les permissions adéquates
RUN chown -R www-data:www-data /var/www \
    && chmod -R 777 /var/www/storage

# Exposer le port 80 pour que l'application soit accessible
EXPOSE 80

CMD ["php-fpm"]
