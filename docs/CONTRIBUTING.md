# Guide de Contribution - OPTIRH

## Table des matières
1. [Architecture du Projet](#architecture-du-projet)
2. [Stack Technologique](#stack-technologique)
3. [Prérequis de Développement](#prérequis-de-développement)
4. [Installation de l'Environnement](#installation-de-lenvironnement)
5. [Structure du Code](#structure-du-code)
6. [Conventions de Code](#conventions-de-code)
7. [Workflow Git](#workflow-git)
8. [Tests](#tests)
9. [Débogage](#débogage)

## Architecture du Projet

OPTIRH est une application de gestion des ressources humaines construite avec Laravel 10 et utilisant une architecture modulaire avec Laravel Modules.

### Modules Principaux
- **OptiHr** : Module principal de gestion RH (employés, absences, documents)
- **Recours** : Module de gestion des recours administratifs

### Structure MVC
```
app/
├── Http/Controllers/        # Contrôleurs organisés par modules
│   ├── OptiHr/             # Contrôleurs RH
│   └── Recours/            # Contrôleurs recours
├── Models/                 # Modèles Eloquent
│   ├── OptiHr/            # Modèles RH
│   └── Recours/           # Modèles recours
├── Services/              # Services métier
├── Mail/                  # Classes d'email
├── Jobs/                  # Tâches en arrière-plan
└── Observers/             # Observateurs de modèles
```

## Stack Technologique

### Backend
- **PHP 8.1+** - Langage principal
- **Laravel 10** - Framework web
- **MySQL** - Base de données
- **Laravel Modules** - Architecture modulaire
- **Spatie Permission** - Gestion des permissions
- **DomPDF** - Génération de PDF

### Frontend
- **Blade Templates** - Moteur de templates
- **Bootstrap 5** - Framework CSS
- **JavaScript ES6+** - Scripts interactifs
- **Vite** - Build tool

### Outils de Développement
- **Composer** - Gestionnaire de dépendances PHP
- **NPM** - Gestionnaire de dépendances JS
- **Docker** - Conteneurisation (optionnel)

## Prérequis de Développement

### Extensions PHP Requises
```bash
# Extensions essentielles
sudo apt-get install php8.3-bcmath    # Calculs mathématiques
sudo apt-get install php8.3-mbstring  # Manipulation de chaînes
sudo apt-get install php8.3-xml       # Traitement XML
sudo apt-get install php8.3-curl      # Requêtes HTTP
sudo apt-get install php8.3-zip       # Compression
sudo apt-get install php8.3-mysql     # Base de données
sudo apt-get install php8.3-gd        # Traitement d'images
```

### Outils de Développement
- Git
- Composer
- Node.js (version 16+)
- Un serveur web (Apache/Nginx) ou Laravel Sail

## Installation de l'Environnement

### 1. Cloner le Projet
```bash
git clone [URL_DU_REPO]
cd optirh
```

### 2. Installation des Dépendances
```bash
# Dépendances PHP
composer install

# Dépendances Node.js
npm install
```

### 3. Configuration de l'Environnement
```bash
# Copier le fichier de configuration
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configuration de la Base de Données
Modifier le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=optirh
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Migration et Seeding
```bash
# Migrations
php artisan migrate

# Données de test (optionnel)
php artisan db:seed
```

### 6. Assets Frontend
```bash
# Développement
npm run dev

# Production
npm run build
```

## Structure du Code

### Contrôleurs
Les contrôleurs suivent le pattern Resource Controller de Laravel :
```php
class EmployeeController extends Controller
{
    public function index()    // Liste des ressources
    public function create()   // Formulaire de création
    public function store()    // Sauvegarde
    public function show()     // Affichage détaillé
    public function edit()     // Formulaire d'édition
    public function update()   // Mise à jour
    public function destroy()  // Suppression
}
```

### Modèles
Les modèles utilisent Eloquent ORM avec :
- Relations définies (`hasMany`, `belongsTo`, `belongsToMany`)
- Mutateurs et Accesseurs pour la transformation des données
- Traits pour les fonctionnalités partagées (`LogsActivity`)

### Services
Les services contiennent la logique métier complexe :
```php
class AbsencePdfService
{
    public function generateLeaveRequest(Absence $absence)
    {
        // Logique de génération PDF
    }
}
```

## Conventions de Code

### Style PHP
Suivre PSR-12 pour le style de code :
- Indentation : 4 espaces
- Noms de classes : PascalCase
- Noms de méthodes : camelCase
- Noms de variables : camelCase
- Constantes : SNAKE_CASE

### Nommage des Fichiers
- Contrôleurs : `EmployeeController.php`
- Modèles : `Employee.php`
- Services : `AbsencePdfService.php`
- Migrations : `yyyy_mm_dd_create_employees_table.php`

### Base de Données
- Tables : snake_case au pluriel (`employees`, `absence_types`)
- Colonnes : snake_case (`first_name`, `created_at`)
- Clés étrangères : `model_id` (`employee_id`, `department_id`)

### Frontend
- Classes CSS : kebab-case
- IDs HTML : kebab-case
- Fonctions JS : camelCase

## Workflow Git

### Branches
- `main` : Branche de production
- `develop` : Branche de développement
- `feature/nom-fonctionnalite` : Nouvelles fonctionnalités
- `hotfix/nom-correction` : Corrections urgentes

### Commits
Format des messages de commit :
```
type(scope): description courte

Description détaillée si nécessaire

- Point spécifique 1
- Point spécifique 2
```

Types de commits :
- `feat` : Nouvelle fonctionnalité
- `fix` : Correction de bug
- `docs` : Documentation
- `style` : Formatage (pas de changement logique)
- `refactor` : Refactorisation
- `test` : Tests
- `chore` : Maintenance

### Pull Requests
1. Créer une branche depuis `develop`
2. Implémenter la fonctionnalité
3. Écrire/mettre à jour les tests
4. Mettre à jour la documentation si nécessaire
5. Créer une Pull Request vers `develop`

## Tests

### Types de Tests
- **Tests Unitaires** : `tests/Unit/`
- **Tests de Fonctionnalités** : `tests/Feature/`

### Exécution des Tests
```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter EmployeeTest

# Avec couverture
php artisan test --coverage
```

### Structure des Tests
```php
class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_employee()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            // ...
        ];

        $response = $this->post('/employees', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('employees', $data);
    }
}
```

## Débogage

### Logs Laravel
```php
// Dans votre code
Log::info('Message informatif');
Log::error('Erreur détectée', ['context' => $data]);

// Voir les logs
tail -f storage/logs/laravel.log
```

### Debug Bar (Développement)
Installer Laravel Debugbar pour le profiling :
```bash
composer require barryvdh/laravel-debugbar --dev
```

### Commandes Artisan Utiles
```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Migrations
php artisan migrate:status
php artisan migrate:rollback

# Queues (si utilisées)
php artisan queue:work
php artisan queue:failed
```

### Base de Données
Utiliser Tinker pour interagir avec les modèles :
```bash
php artisan tinker

# Dans Tinker
>>> Employee::count()
>>> Employee::first()
>>> User::with('employee')->get()
```

## Bonnes Pratiques

1. **Sécurité** : Toujours valider les entrées utilisateur
2. **Performance** : Utiliser l'eager loading pour éviter N+1 queries
3. **Cache** : Mettre en cache les requêtes coûteuses
4. **Validation** : Utiliser les Form Requests pour la validation
5. **Traduction** : Utiliser les fichiers de langue pour l'internationalisation
6. **SOLID** : Respecter les principes SOLID dans l'architecture

## Contacts

Pour toute question sur le développement, contacter l'équipe technique ou créer une issue dans le repository.