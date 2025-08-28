# Documentation OPTIRH

Bienvenue dans la documentation complète du système OPTIRH - une plateforme moderne de gestion des ressources humaines.

## 📚 Vue d'ensemble

OPTIRH est une application web complète de gestion des ressources humaines développée avec Laravel 10, offrant deux modules principaux :
- **OptiHR** : Gestion complète des RH (employés, absences, documents)
- **Recours** : Traitement des recours administratifs

## 📋 Table des matières

### Pour les Utilisateurs
- **[Manuel Utilisateur](USER_GUIDE.md)** - Guide complet d'utilisation de la plateforme
  - Interface et navigation
  - Gestion du personnel
  - Demandes d'absences et de documents
  - Module de recours
  - FAQ et support

### Pour les Développeurs
- **[Guide de Contribution](CONTRIBUTING.md)** - Documentation technique pour les développeurs
  - Architecture du projet
  - Stack technologique
  - Conventions de code
  - Workflow Git
  - Tests et débogage

### Pour les Administrateurs
- **[Guide d'Installation](INSTALLATION.md)** - Instructions complètes de déploiement
  - Prérequis système
  - Installation locale et Docker
  - Configuration serveur
  - Déploiement production
  - Maintenance et dépannage

### Pour les Intégrateurs
- **[Documentation API](API_DOCUMENTATION.md)** - Référence complète de l'API REST
  - Authentification et sécurité
  - Endpoints disponibles
  - Exemples de code
  - Webhooks et intégrations

## 🚀 Démarrage Rapide

### 1. Installation Rapide
```bash
# Cloner le projet
git clone [URL_DU_REPO]
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
```

### 2. Accès à l'Application
- **URL** : http://localhost:8000
- **Admin par défaut** : admin@optirh.com / password
- **Documentation API** : http://localhost:8000/api/documentation

## 🏗️ Architecture

```
optirh/
├── app/                    # Code de l'application
│   ├── Http/Controllers/   # Contrôleurs par modules
│   ├── Models/            # Modèles Eloquent
│   ├── Services/          # Services métier
│   └── Mail/              # Classes d'email
├── resources/views/       # Templates Blade
│   ├── modules/opti-hr/   # Vues OptiHR
│   └── modules/recours/   # Vues Recours
├── database/              # Migrations et seeders
├── public/                # Assets compilés
└── docs/                  # Documentation
```

## 🔧 Fonctionnalités Principales

### Module OptiHR
- ✅ **Gestion du Personnel** - CRUD employés avec informations complètes
- ✅ **Gestion des Absences** - Workflow de validation hiérarchique
- ✅ **Demandes de Documents** - Génération automatique de documents RH
- ✅ **Publications** - Système d'annonces internes
- ✅ **Tableaux de Bord** - Statistiques et indicateurs RH
- ✅ **Gestion des Permissions** - Rôles et autorisations granulaires

### Module Recours
- ✅ **Soumission de Recours** - Interface de dépôt de recours
- ✅ **Circuit de Traitement** - Workflow avec états d'avancement
- ✅ **Commission DAC** - Gestion des décisions collégiales
- ✅ **Notifications** - Alertes automatiques par email
- ✅ **Statistiques** - Tableaux de bord spécialisés

## 🔐 Sécurité

- **Authentification** : Laravel Sanctum pour l'API
- **Autorisation** : Spatie Permission pour les rôles
- **Validation** : Form Requests pour la validation des données
- **Audit** : Logs d'activité pour toutes les actions sensibles
- **HTTPS** : Configuration SSL/TLS recommandée en production

## 📊 Stack Technologique

### Backend
- **PHP 8.1+** avec **Laravel 10**
- **MySQL 8.0+** pour la base de données
- **Redis** pour le cache et les sessions
- **Laravel Modules** pour l'architecture modulaire

### Frontend
- **Blade Templates** pour les vues
- **Bootstrap 5** pour l'interface
- **JavaScript ES6+** pour l'interactivité
- **Vite** pour le build des assets

### Infrastructure
- **Nginx** comme serveur web
- **PHP-FPM** pour l'exécution PHP
- **Docker** pour la conteneurisation
- **Supervisor** pour les queues en production

## 📈 Roadmap

### Version 1.1 (En cours)
- [ ] Module de paie intégré
- [ ] Interface mobile responsive
- [ ] API REST complète
- [ ] Intégrations tierces (LDAP, SSO)

### Version 1.2 (Prévu)
- [ ] Module de formation
- [ ] Système de workflow avancé
- [ ] Reporting avancé avec graphiques
- [ ] Module d'évaluation des performances

## 🤝 Contribution

Nous accueillons les contributions ! Consultez le [Guide de Contribution](CONTRIBUTING.md) pour :
- Standards de code
- Processus de développement
- Tests et qualité
- Soumission de Pull Requests

### Développement Local
```bash
# Installation des outils de développement
composer install
npm install

# Tests
php artisan test

# Style de code
./vendor/bin/pint

# Développement avec hot-reload
npm run dev
```

## 📞 Support

### Documentation
- **Wiki** : Documentation interne du projet
- **API Docs** : Interface Swagger disponible
- **Changelog** : Historique des versions

### Assistance
- **Issues GitHub** : Pour les bugs et demandes de fonctionnalités
- **Email Support** : support@optirh.com
- **Formation** : Sessions disponibles pour les nouveaux utilisateurs

## 📄 Licence

Ce projet est sous licence [MIT](../LICENSE). Voir le fichier LICENSE pour plus de détails.

## 🏆 Équipe

Développé avec ❤️ par l'équipe OPTIRH :
- **Architecture** : Conception modulaire et évolutive
- **Backend** : Laravel et API REST
- **Frontend** : Interface utilisateur moderne
- **DevOps** : Infrastructure et déploiement

---

## 📚 Liens Rapides

| Document | Description | Audience |
|----------|-------------|----------|
| [USER_GUIDE.md](USER_GUIDE.md) | Manuel utilisateur complet | Utilisateurs finaux |
| [CONTRIBUTING.md](CONTRIBUTING.md) | Guide technique développeur | Développeurs |
| [INSTALLATION.md](INSTALLATION.md) | Instructions d'installation | Administrateurs système |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Documentation API REST | Intégrateurs |

---

*Dernière mise à jour : Janvier 2025 - Version 1.0*