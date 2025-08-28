# OPTIRH
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-orange.svg)](https://www.mysql.com/)

**OPTIRH** est une plateforme complète de gestion des ressources humaines développée avec Laravel 10. Elle offre une solution moderne et modulaire pour la gestion du personnel, des absences, des documents RH et des recours administratifs.

## ✨ Fonctionnalités Principales

### 🏢 Module OptiHR - Gestion RH
- **👥 Gestion du Personnel** - CRUD complet des employés avec informations détaillées
- **📅 Gestion des Absences** - Workflow de validation hiérarchique des congés
- **📄 Demandes de Documents** - Génération automatique de documents RH (attestations, certificats)
- **📢 Publications** - Système d'annonces et communications internes
- **📊 Tableaux de Bord** - Statistiques et indicateurs RH en temps réel
- **🔐 Gestion des Permissions** - Système de rôles et autorisations granulaires

### ⚖️ Module Recours - Gestion Administrative
- **📝 Soumission de Recours** - Interface de dépôt de recours administratifs
- **🔄 Circuit de Traitement** - Workflow avec états d'avancement
- **👨‍⚖️ Commission DAC** - Gestion des décisions collégiales
- **🔔 Notifications** - Alertes automatiques par email
- **📈 Statistiques** - Tableaux de bord spécialisés pour les recours

## 🚀 Démarrage Rapide

### Prérequis
- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js & NPM
- Extension PHP bcmath

### Installation
```bash
# Cloner le projet
git clone https://github.com/Dreykovic/optirh.git
cd optirh

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate --seed

# Compiler les assets
npm run build

# Démarrer le serveur de développement
php artisan serve
```

### 🔗 Accès à l'Application
- **Application** : http://localhost:8000
- **Admin par défaut** : admin@optirh.com / password

## 📚 Documentation

- **📖 [Manuel Utilisateur](docs/USER_GUIDE.md)** - Guide complet pour les utilisateurs
- **⚙️ [Guide d'Installation](docs/INSTALLATION.md)** - Instructions de déploiement détaillées
- **👨‍💻 [Guide de Contribution](docs/CONTRIBUTING.md)** - Documentation pour les développeurs
- **🔌 [Documentation API](docs/API_DOCUMENTATION.md)** - Référence complète de l'API REST
- **🔒 [Politique de Sécurité](docs/SECURITY.md)** - Sécurité et signalement de vulnérabilités

## 🏗️ Technologies

- **Backend** : Laravel 10, PHP 8.1+, MySQL 8.0+
- **Frontend** : Blade Templates, Bootstrap 5, JavaScript ES6+
- **Authentification** : Laravel Sanctum
- **Permissions** : Spatie Permission
- **PDF** : DomPDF, FPDI
- **Build** : Vite, NPM
- **Architecture** : Laravel Modules (modulaire)

## 🤝 Contribution

Les contributions sont les bienvenues ! Consultez notre [Guide de Contribution](docs/CONTRIBUTING.md) pour commencer.

### Développement Local
```bash
# Installation des outils de développement
composer install
npm install

# Tests
php artisan test

# Style de code
./vendor/bin/pint
```

## 🐛 Signalement de Bugs

Trouvé un bug ? [Créez une issue](https://github.com/Dreykovic/optirh/issues/new?template=bug_report.yml) en utilisant notre template de rapport de bug.

## ✨ Demandes de Fonctionnalités

Avez-vous une idée d'amélioration ? [Proposez une fonctionnalité](https://github.com/Dreykovic/optirh/issues/new?template=feature_request.yml) !

## 📞 Support

- **📖 Documentation** : [Guides dans /docs](docs/)
- **🐛 Issues** : [GitHub Issues](https://github.com/Dreykovic/optirh/issues)
- **❓ Questions** : [Créer une question](https://github.com/Dreykovic/optirh/issues/new?template=question.yml)
- **📧 Email** : support@optirh.com

## 📄 Licence

Ce projet est sous licence [MIT](LICENSE).

## 🏆 Équipe

Développé avec ❤️ par [@Dreykovic](https://github.com/Dreykovic) et l'équipe OPTIRH.

---

⭐ **N'hésitez pas à donner une étoile si ce projet vous est utile !**
