# Métadonnées du Repository GitHub - OPTIRH

## Description du Projet
**Description courte :**
OPTIRH - Plateforme complète de gestion des ressources humaines avec modules OptiHR et Recours, développée avec Laravel 10.

**Description longue :**
OPTIRH est une solution moderne et complète de gestion des ressources humaines conçue pour les entreprises et administrations. Elle offre deux modules intégrés : OptiHR pour la gestion complète du personnel (employés, absences, documents RH, publications) et Recours pour le traitement des recours administratifs avec workflow de validation. Développée avec Laravel 10, l'application propose une interface intuitive, une architecture modulaire et une API REST complète.

## Topics/Tags GitHub
```
laravel
php
hr-management
human-resources
employee-management
leave-management
document-management
administrative-appeals
modular-architecture
rest-api
bootstrap
mysql
redis
pdf-generation
workflow
rbac
sanctum
spatie-permission
laravel-modules
enterprise-software
```

## Website/Homepage
https://optirh.votre-domaine.com

## Documentation
https://github.com/Dreykovic/optirh/tree/main/docs

## Licence
MIT

## Langue Principale
PHP (85%), JavaScript (10%), Blade (3%), CSS (2%)

## Fonctionnalités Clés
- ✅ Gestion complète du personnel
- ✅ Workflow de validation des absences
- ✅ Génération automatique de documents RH
- ✅ Système de recours administratifs
- ✅ Tableaux de bord avec statistiques
- ✅ API REST avec authentification
- ✅ Architecture modulaire
- ✅ Système de permissions granulaires
- ✅ Notifications email automatiques
- ✅ Interface responsive

## Technologies Utilisées
- **Backend:** Laravel 10, PHP 8.1+
- **Frontend:** Blade Templates, Bootstrap 5, JavaScript
- **Base de données:** MySQL 8.0+
- **Cache:** Redis
- **Authentification:** Laravel Sanctum
- **Permissions:** Spatie Permission
- **PDF:** DomPDF, FPDI
- **Build:** Vite, NPM
- **Conteneurs:** Docker (optionnel)

## Structure du Projet
```
optirh/
├── app/Http/Controllers/
│   ├── OptiHr/          # Contrôleurs RH
│   └── Recours/         # Contrôleurs recours
├── app/Models/
│   ├── OptiHr/          # Modèles RH  
│   └── Recours/         # Modèles recours
├── resources/views/modules/
│   ├── opti-hr/         # Vues module RH
│   └── recours/         # Vues module recours
├── docs/                # Documentation complète
└── database/migrations/ # Schéma de base de données
```