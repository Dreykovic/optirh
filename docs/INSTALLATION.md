# Guide d'Installation - OPTIRH

## Table des matières
1. [Prérequis Système](#prérequis-système)
2. [Installation Locale](#installation-locale)
3. [Installation avec Docker](#installation-avec-docker)
4. [Configuration](#configuration)
5. [Déploiement en Production](#déploiement-en-production)
6. [Maintenance](#maintenance)
7. [Dépannage](#dépannage)

## Prérequis Système

### Serveur Web
- **OS** : Ubuntu 20.04+ / CentOS 8+ / Windows 10+
- **RAM** : 2GB minimum, 4GB recommandé
- **Stockage** : 10GB minimum d'espace libre
- **Processeur** : 2 cœurs minimum

### Logiciels Requis

#### PHP 8.1+
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.1 php8.1-fpm php8.1-cli

# CentOS/RHEL
sudo dnf install php php-fpm php-cli
```

#### Extensions PHP Obligatoires
```bash
# Ubuntu/Debian
sudo apt install php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl \
                 php8.1-zip php8.1-bcmath php8.1-gd php8.1-json \
                 php8.1-tokenizer php8.1-ctype php8.1-fileinfo

# CentOS/RHEL
sudo dnf install php-mysql php-mbstring php-xml php-curl \
                 php-zip php-bcmath php-gd php-json
```

#### Base de Données
```bash
# MySQL 8.0+
sudo apt install mysql-server mysql-client

# MariaDB 10.6+ (alternative)
sudo apt install mariadb-server mariadb-client
```

#### Serveur Web
```bash
# Nginx (recommandé)
sudo apt install nginx

# Apache (alternative)
sudo apt install apache2
```

#### Node.js et NPM
```bash
# Via NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs

# Vérifier les versions
node --version  # v18.0.0+
npm --version   # v8.0.0+
```

#### Composer
```bash
# Installation globale
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Vérifier l'installation
composer --version
```

## Installation Locale

### 1. Cloner le Repository
```bash
# Cloner le projet
git clone https://github.com/votre-org/optirh.git
cd optirh

# Ou télécharger et extraire l'archive
wget https://github.com/votre-org/optirh/archive/main.zip
unzip main.zip
cd optirh-main
```

### 2. Installation des Dépendances

#### Dépendances PHP
```bash
# Installation des packages Composer
composer install --no-dev --optimize-autoloader

# Pour le développement
composer install
```

#### Dépendances JavaScript
```bash
# Installation des packages NPM
npm install

# Compilation des assets
npm run build

# Pour le développement avec hot-reload
npm run dev
```

### 3. Configuration de l'Environnement

#### Fichier .env
```bash
# Copier le fichier d'exemple
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

#### Configuration Base de Données
Modifier le fichier `.env` :
```env
# Configuration de base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=optirh
DB_USERNAME=optirh_user
DB_PASSWORD=votre_mot_de_passe_securise

# Configuration d'application
APP_NAME="OPTIRH"
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Configuration email
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-domaine.com
MAIL_PORT=587
MAIL_USERNAME=noreply@votre-domaine.com
MAIL_PASSWORD=mot_de_passe_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Configuration Base de Données

#### Création de la Base de Données
```bash
# Connexion MySQL
mysql -u root -p

# Création de la base et de l'utilisateur
CREATE DATABASE optirh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'optirh_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON optirh.* TO 'optirh_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Migrations et Données Initiales
```bash
# Exécution des migrations
php artisan migrate

# Seeding des données de base
php artisan db:seed

# Ou combiné
php artisan migrate:fresh --seed
```

### 5. Permissions de Fichiers
```bash
# Propriétaire des fichiers
sudo chown -R www-data:www-data /chemin/vers/optirh

# Permissions des dossiers
sudo find /chemin/vers/optirh -type d -exec chmod 755 {} \;

# Permissions des fichiers
sudo find /chemin/vers/optirh -type f -exec chmod 644 {} \;

# Permissions spéciales pour les dossiers sensibles
sudo chmod -R 775 /chemin/vers/optirh/storage
sudo chmod -R 775 /chemin/vers/optirh/bootstrap/cache
```

### 6. Configuration Nginx

#### Fichier de Configuration
Créer `/etc/nginx/sites-available/optirh` :
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name votre-domaine.com www.votre-domaine.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    
    server_name votre-domaine.com www.votre-domaine.com;
    root /chemin/vers/optirh/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static files caching
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }
}
```

#### Activation du Site
```bash
# Lien symbolique
sudo ln -s /etc/nginx/sites-available/optirh /etc/nginx/sites-enabled/

# Test de la configuration
sudo nginx -t

# Redémarrage d'Nginx
sudo systemctl restart nginx
sudo systemctl enable nginx
```

### 7. Configuration PHP-FPM
Modifier `/etc/php/8.1/fpm/pool.d/www.conf` :
```ini
[www]
user = www-data
group = www-data
listen = /run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500

# Optimisations PHP
php_admin_value[memory_limit] = 256M
php_admin_value[upload_max_filesize] = 20M
php_admin_value[post_max_size] = 25M
php_admin_value[max_execution_time] = 300
php_admin_value[max_input_time] = 300
```

Redémarrer PHP-FPM :
```bash
sudo systemctl restart php8.1-fpm
sudo systemctl enable php8.1-fpm
```

## Installation avec Docker

### 1. Fichiers Docker

#### Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

#### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: optirh-app
    container_name: optirh-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - optirh-network

  webserver:
    image: nginx:alpine
    container_name: optirh-webserver
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
      - ./docker/nginx/ssl/:/etc/nginx/ssl/
    networks:
      - optirh-network

  database:
    image: mysql:8.0
    container_name: optirh-database
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: optirh
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_PASSWORD: user_password
      MYSQL_USER: optirh_user
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    ports:
      - "3306:3306"
    volumes:
      - optirh-mysql-data:/var/lib/mysql
    networks:
      - optirh-network

  redis:
    image: redis:alpine
    container_name: optirh-redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    networks:
      - optirh-network

networks:
  optirh-network:
    driver: bridge

volumes:
  optirh-mysql-data:
    driver: local
```

### 2. Démarrage avec Docker
```bash
# Construction et démarrage des conteneurs
docker-compose up -d --build

# Installation des dépendances
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build

# Configuration
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate

# Migrations
docker-compose exec app php artisan migrate --seed
```

## Configuration

### 1. Configuration Avancée

#### Cache et Sessions
```env
# Cache Driver
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_DRIVER=redis

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Files et Uploads
```env
# Filesystem
FILESYSTEM_DRIVER=local

# Pour utilisation S3
# FILESYSTEM_DRIVER=s3
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=
# AWS_BUCKET=
```

### 2. Optimisations Laravel
```bash
# Cache des configurations
php artisan config:cache

# Cache des routes
php artisan route:cache

# Cache des vues
php artisan view:cache

# Optimisation de l'autoloader
composer dump-autoload --optimize
```

### 3. Configuration des Tâches Planifiées
Ajouter au crontab (`crontab -e`) :
```bash
# Laravel Scheduler
* * * * * cd /chemin/vers/optirh && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Configuration des Queues
Pour traitement en arrière-plan :
```bash
# Démarrage manuel
php artisan queue:work

# Avec Supervisor (production)
sudo apt install supervisor
```

Configuration Supervisor (`/etc/supervisor/conf.d/optirh-worker.conf`) :
```ini
[program:optirh-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /chemin/vers/optirh/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/optirh-worker.log
stopwaitsecs=3600
```

## Déploiement en Production

### 1. Checklist de Sécurité

#### Variables d'Environnement
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Clés de sécurité
APP_KEY=base64:LONGUE_CLE_ALEATOIRE_SECURISEE
```

#### Permissions de Fichiers
```bash
# Propriétaire
sudo chown -R www-data:www-data /chemin/vers/optirh

# Permissions restrictives
sudo find /chemin/vers/optirh -type f -exec chmod 644 {} \;
sudo find /chemin/vers/optirh -type d -exec chmod 755 {} \;

# Dossiers spéciaux
sudo chmod -R 775 storage bootstrap/cache
```

#### Fichiers Sensibles
```bash
# Protéger .env
sudo chmod 600 .env
sudo chown root:www-data .env

# Supprimer les fichiers de développement
rm -rf tests
rm README.md
```

### 2. Optimisations Production
```bash
# Optimisations Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimisation Composer
composer install --no-dev --optimize-autoloader

# Compilation des assets
npm run build
```

### 3. Monitoring et Logs

#### Logs Laravel
```bash
# Rotation des logs
sudo nano /etc/logrotate.d/optirh
```

Configuration :
```
/chemin/vers/optirh/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
}
```

#### Monitoring Système
```bash
# Installation d'outils de monitoring
sudo apt install htop iotop netstat-nat

# Surveillance des processus
ps aux | grep php-fpm
ps aux | grep nginx
```

## Maintenance

### 1. Sauvegarde

#### Script de Sauvegarde
```bash
#!/bin/bash
# backup-optirh.sh

DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/backup/optirh"
APP_DIR="/chemin/vers/optirh"

# Création du répertoire de backup
mkdir -p $BACKUP_DIR

# Sauvegarde de la base de données
mysqldump -u optirh_user -p optirh > $BACKUP_DIR/database_$DATE.sql

# Sauvegarde des fichiers uploadés
tar -czf $BACKUP_DIR/uploads_$DATE.tar.gz $APP_DIR/storage/app/public

# Sauvegarde de la configuration
cp $APP_DIR/.env $BACKUP_DIR/.env_$DATE

# Suppression des anciennes sauvegardes (30 jours)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

#### Automatisation
```bash
# Ajout au crontab
0 2 * * * /chemin/vers/backup-optirh.sh
```

### 2. Mise à Jour

#### Processus de Mise à Jour
```bash
# Sauvegarde avant mise à jour
./backup-optirh.sh

# Mode maintenance
php artisan down

# Mise à jour du code
git pull origin main

# Mise à jour des dépendances
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Migrations
php artisan migrate

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Sortie du mode maintenance
php artisan up
```

### 3. Nettoyage Régulier
```bash
# Nettoyage des logs d'activité (commande personnalisée)
php artisan cleanup:activity-logs

# Nettoyage des sessions expirées
php artisan session:gc

# Nettoyage du cache
php artisan cache:clear
php artisan view:clear
```

## Dépannage

### 1. Problèmes Courants

#### Erreurs de Permissions
```bash
# Symptôme : Erreur 500 ou accès refusé
# Solution :
sudo chown -R www-data:www-data /chemin/vers/optirh
sudo chmod -R 775 storage bootstrap/cache
```

#### Base de Données Inaccessible
```bash
# Vérification de la connexion
php artisan tinker
>>> DB::connection()->getPdo();

# Test de connexion MySQL
mysql -u optirh_user -p optirh
```

#### Problèmes de Cache
```bash
# Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 2. Logs de Débogage

#### Activation des Logs Détaillés
```env
# Dans .env (temporairement)
APP_DEBUG=true
LOG_LEVEL=debug
```

#### Consultation des Logs
```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Nginx
tail -f /var/log/nginx/error.log

# Logs PHP-FPM
tail -f /var/log/php8.1-fpm.log
```

### 3. Performance

#### Profiling
```bash
# Installation de debugbar (développement uniquement)
composer require barryvdh/laravel-debugbar --dev

# Analyse des requêtes lentes
# Dans .env
DB_LOG_QUERIES=true
DB_SLOW_QUERY_LOG=true
```

#### Optimisations MySQL
```sql
-- Dans MySQL
SHOW PROCESSLIST;
SHOW STATUS LIKE 'slow_queries';
```

## Support

Pour toute assistance technique :
- **Documentation** : Consultez ce guide et les autres fichiers docs/
- **Logs** : Vérifiez les logs système et application
- **Community** : Issues GitHub du projet
- **Support** : Contact de l'équipe technique

---

*Guide d'installation OPTIRH - Version 1.0*