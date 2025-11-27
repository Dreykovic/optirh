# Analyse de la Gestion des Rôles et Permissions - Module OptiHR

Ce document détaille l'architecture de gestion des rôles et permissions implémentée dans le module OptiHR de l'application OPTIRH.

## 1. Structure des Rôles

**7 rôles définis** dans `database/seeders/RoleSeeder.php:420-426` :

| Rôle | Description | Accès Module |
|------|-------------|--------------|
| **ADMIN** | Accès total à toutes les permissions | `access-un-all` |
| **GRH** | Gestionnaire RH | `access-un-opti-hr` uniquement |
| **DSAF** | Directeur Services Administratifs et Financiers | `access-un-opti-hr` uniquement |
| **DG** | Directeur Général | `access-un-all` |
| **EMPLOYEE** | Employé standard | `access-un-opti-hr` uniquement |
| **standart** | Utilisateur standard (recours) | `access-un-all` |
| **DRAJ** | Direction Juridique | `access-un-all` + `appeal-actions` |

## 2. Convention de Nommage des Permissions

Les permissions suivent un pattern français : `{action}-un(e)-{ressource}`

**Actions disponibles :**
- `voir` : Lecture/consultation
- `écrire` : Modification
- `créer` : Création
- `configurer` : Configuration/paramétrage
- `access` : Accès à un module

## 3. Permissions par Ressource OptiHR

```
┌─────────────────┬──────────┬──────────┬──────────┬─────────────┐
│ Ressource       │ voir     │ écrire   │ créer    │ configurer  │
├─────────────────┼──────────┼──────────┼──────────┼─────────────┤
│ employee        │ ✓        │ ✓        │ ✓        │ ✓           │
│ absence         │ ✓        │ ✓        │ ✓        │ ✓           │
│ document        │ ✓        │ ✓        │ ✓        │ ✓           │
│ publication     │ ✓        │ ✓        │ ✓        │ ✓           │
│ credentials     │ ✓        │ ✓        │ ✓        │ ✓           │
│ role            │ ✓        │ ✓        │ ✓        │ ✓           │
│ férié           │ ✓        │ ✓        │ ✓        │ ✓           │
│ journal         │ ✓        │ ✓        │ ✓        │ ✓           │
│ attendance      │ ✓        │ ✓        │ ✓        │ ✓           │
│ compte          │ ✓        │ ✓        │ ✓        │ ✓           │
└─────────────────┴──────────┴──────────┴──────────┴─────────────┘
```

**Permissions spéciales :**
- `send-paie` : Envoi des bulletins de paie (GRH uniquement)
- `voir-un-all`, `écrire-un-all`, `créer-un-all`, `configurer-un-all` : Permissions globales

## 4. Matrice des Permissions par Rôle (OptiHR)

### 4.1 Gestion des Employés

| Permission | ADMIN | GRH | DSAF | DG | EMPLOYEE |
|------------|:-----:|:---:|:----:|:--:|:--------:|
| voir-un-employee | ✓ | ✓ | ✓ | ✓ | ✗ |
| écrire-un-employee | ✓ | ✓ | ✗ | ✗ | ✗ |
| créer-un-employee | ✓ | ✓ | ✗ | ✗ | ✗ |
| configurer-un-employee | ✓ | ✓ | ✗ | ✓ | ✗ |

### 4.2 Gestion des Absences

| Permission | ADMIN | GRH | DSAF | DG | EMPLOYEE |
|------------|:-----:|:---:|:----:|:--:|:--------:|
| voir-une-absence | ✓ | ✓ | ✓ | ✓ | ✓ |
| écrire-une-absence | ✓ | ✓ | ✓ | ✓ | ✗ |
| créer-une-absence | ✓ | ✓ | ✓ | ✓ | ✓ |
| configurer-une-absence | ✓ | ✓ | ✓ | ✗ | ✗ |

### 4.3 Gestion des Documents

| Permission | ADMIN | GRH | DSAF | DG | EMPLOYEE |
|------------|:-----:|:---:|:----:|:--:|:--------:|
| voir-un-document | ✓ | ✓ | ✓ | ✓ | ✓ |
| écrire-un-document | ✓ | ✓ | ✓ | ✓ | ✗ |
| créer-un-document | ✓ | ✓ | ✓ | ✓ | ✓ |
| configurer-un-document | ✓ | ✓ | ✓ | ✗ | ✗ |

### 4.4 Gestion des Publications

| Permission | ADMIN | GRH | DSAF | DG | EMPLOYEE |
|------------|:-----:|:---:|:----:|:--:|:--------:|
| voir-une-publication | ✓ | ✓ | ✓ | ✓ | ✓ |
| écrire-une-publication | ✓ | ✓ | ✗ | ✗ | ✗ |
| créer-une-publication | ✓ | ✓ | ✗ | ✗ | ✗ |
| configurer-une-publication | ✓ | ✓ | ✗ | ✗ | ✗ |

### 4.5 Gestion des Credentials (Utilisateurs)

| Permission | ADMIN | GRH | DSAF | DG | EMPLOYEE |
|------------|:-----:|:---:|:----:|:--:|:--------:|
| voir-un-credentials | ✓ | ✓ | ✗ | ✗ | ✗ |
| écrire-un-credentials | ✓ | ✓ | ✗ | ✗ | ✓ |
| créer-un-credentials | ✓ | ✓ | ✗ | ✗ | ✗ |
| configurer-un-credentials | ✓ | ✓ | ✗ | ✗ | ✗ |

### 4.6 Permissions Spéciales

| Permission | ADMIN | GRH | DSAF | DG | EMPLOYEE |
|------------|:-----:|:---:|:----:|:--:|:--------:|
| send-paie | ✓ | ✓ | ✗ | ✗ | ✗ |
| access-un-opti-hr | ✓ | ✓ | ✓ | ✗ | ✓ |
| access-un-all | ✓ | ✗ | ✗ | ✓ | ✗ |

## 5. Application dans les Contrôleurs

### 5.1 Pattern de Middleware

Les permissions sont appliquées dans le constructeur des contrôleurs via middleware :

**Exemple dans `AbsenceController.php:28-30` :**
```php
public function __construct()
{
    $this->middleware(['permission:voir-une-absence|écrire-une-absence|créer-une-absence|configurer-une-absence|voir-un-tout'], ['only' => ['index']]);
    $this->middleware(['permission:créer-une-absence|créer-un-tout'], ['only' => ['store', 'cancel', 'create']]);
    $this->middleware(['permission:écrire-une-absence|écrire-un-tout'], ['only' => ['approve', 'reject', 'comment']]);
}
```

**Pattern utilisé :** `permission:perm1|perm2|perm3` (OU logique - une seule permission suffit)

### 5.2 Contrôleurs Protégés

| Contrôleur | Méthodes Protégées |
|------------|-------------------|
| `AbsenceController` | index, store, cancel, create, approve, reject, comment |
| `AbsenceTypeController` | index, store, update, create, destroy |
| `DocumentRequestController` | index, download, store, cancel, create |
| `DocumentTypeController` | index, store, update, create, destroy |
| `HolidayController` | index, store, create, destroy, update |
| `PublicationController` | index, store, destroy, updateStatus |
| `AnnualDecisionController` | index, show, store, storeOrUpdate, setCurrent, downloadPdf, destroy |
| `UserController` | index, store |

### 5.3 Contrôleurs avec Protection Désactivée

> **Attention** : Les middlewares sont commentés dans ces contrôleurs :
> - `EmployeeController.php:22-26`
> - `RoleController.php:13-17`

## 6. Contrôle dans les Vues (Blade)

### 6.1 Directives Utilisées

- `@can('permission')` : Vérifie une permission unique
- `@canany(['perm1', 'perm2'])` : Vérifie au moins une permission parmi la liste
- `@cannot('permission')` : Inverse de @can

### 6.2 Sidebar Navigation (`navs.blade.php`)

```blade
<!-- Menu Absences -->
@can('configurer-une-absence')
    <li><a href="{{ route('absenceTypes.index') }}">Types d'absences</a></li>
    <li><a href="{{ route('decisions.show') }}">Décision Courante</a></li>
@endcan

@can('voir-un-all')
    <li><a href="{{ route('holidays.index') }}">Jours Fériés</a></li>
@endcan

<!-- Menu Documents -->
@can('configurer-un-document')
    <li><a href="{{ route('documentTypes.index') }}">Types de Document</a></li>
@endcan

<!-- Menu Utilisateurs (visible uniquement pour ceux qui gèrent les credentials) -->
@can('voir-un-credentials')
    <li class="collapsed">
        <a>Utilisateurs</a>
        <ul>
            <li><a href="{{ route('credentials.index') }}">Identifiants</a></li>
            <li><a href="{{ route('roles.index') }}">Roles</a></li>
            <li><a href="{{ route('permissions.index') }}">Permissions</a></li>
            <li><a href="{{ route('activity-logs.index') }}">Journal Des Actions</a></li>
        </ul>
    </li>
@endcan

<!-- Menu Personnel -->
@can('voir-un-employee')
    <li class="collapsed">
        <a>Personnel</a>
        <ul>
            <li><a href="{{ route('directions') }}">Directions</a></li>
            <li><a href="{{ route('membres.pages') }}">Membres</a></li>
            @can('send-paie')
                <li><a href="{{ route('membres.pay-form') }}">Envoi de bulletins de paie</a></li>
            @endcan
            <li><a href="{{ route('contrats.index') }}">Contrats</a></li>
        </ul>
    </li>
@endcan
```

### 6.3 Gateway (Accès aux Modules)

```blade
<!-- Accès au module OptiHR -->
@canany(['access-un-all', 'access-un-opti-hr'])
    <a href="{{ route('opti-hr.home') }}" class="app-card">OptiHR</a>
@endcanany

<!-- Accès au module Recours -->
@canany(['access-un-all', 'access-un-recours'])
    <a href="{{ route('recours.home') }}" class="app-card">Recours</a>
@endcanany
```

## 7. Workflow d'Approbation Hiérarchique

### 7.1 Niveaux d'Approbation

| Niveau | Description | Approbateur |
|--------|-------------|-------------|
| `ZERO` | Demande initiale | N+1 direct (ou GRH si pas de N+1) |
| `ONE` | Validation RH | GRH |
| `TWO` | Validation finale | DG |

### 7.2 Logique d'Affichage des Actions

Dans `actions.blade.php:15-23`, les boutons Approuver/Rejeter sont affichés selon :

```blade
@if (
    ($absence->level == 'ONE' && auth()->user()->hasRole('GRH')) ||
    ($absence->level == 'TWO' && auth()->user()->hasRole('DG')) ||
    ($absence->level == 'ZERO' &&
        auth()->user()->employee->duties->firstWhere('evolution', 'ON_GOING')->job_id ===
        $absence->duty->job->n_plus_one_job_id) ||
    (in_array($absence->level, ['ZERO']) &&
        auth()->user()->hasRole('GRH') &&
        $absence->duty->job->n_plus_one_job_id === null)
)
    <!-- Boutons Approuver / Rejeter / Configurer -->
@endif
```

### 7.3 Flux de Notification

```
Employé crée demande (ZERO)
    │
    ▼
N+1 direct reçoit notification
    │ (approuve)
    ▼
GRH reçoit demande (ONE)
    │ (approuve)
    ▼
DG reçoit demande (TWO)
    │ (approuve)
    ▼
Demande APPROVED
```

## 8. Points d'Attention / Problèmes Identifiés

### 8.1 Middlewares Désactivés

Les protections sont commentées dans certains contrôleurs :

```php
// EmployeeController.php:22-26
// $this->middleware(['permission:voir-un-employee|configurer-un-employee|voir-un-tout'], ['only' => ['index']]);
// $this->middleware(['permission:créer-un-employee|créer-un-tout'], ['only' => ['store', 'update', 'create']]);
// ...

// RoleController.php:13-17
// $this->middleware(['permission:voir-un-role|écrire-un-role|créer-un-role|voir-un-tout'], ['only' => ['index', 'store', 'get_permissions', 'show']]);
// ...
```

### 8.2 Incohérence de Nommage

- Dans les middlewares : `voir-un-tout`
- Dans la base de données : `voir-un-all`

Ces permissions ne correspondent pas, ce qui peut causer des problèmes d'accès.

### 8.3 Permissions Non Utilisées

Les permissions globales créées mais rarement référencées correctement :
- `voir-un-all`
- `écrire-un-all`
- `créer-un-all`
- `configurer-un-all`

### 8.4 Restrictions du Rôle GRH

Le GRH ne peut pas gérer les rôles (permissions commentées dans la liste `hr_permissions_list`).

### 8.5 Limitations du Rôle EMPLOYEE

L'employé n'a pas `voir-un-employee`, donc il ne peut pas consulter les fiches des autres employés.

## 9. Résumé de l'Architecture

```
┌──────────────────────────────────────────────────────────────┐
│                     GATEWAY (/)                               │
│  @canany(['access-un-all', 'access-un-opti-hr'])             │
└────────────────────────┬─────────────────────────────────────┘
                         │
┌────────────────────────▼─────────────────────────────────────┐
│                    OptiHR Module                              │
├──────────────────────────────────────────────────────────────┤
│  COUCHE CONTRÔLEUR                                           │
│  └── Middleware permission dans __construct()                │
│      └── Pattern: permission:perm1|perm2|perm3               │
├──────────────────────────────────────────────────────────────┤
│  COUCHE VUE                                                  │
│  └── Directives Blade: @can, @canany, @cannot                │
│      └── Affichage conditionnel des menus et actions         │
├──────────────────────────────────────────────────────────────┤
│  WORKFLOW MÉTIER                                             │
│  └── hasRole() pour approbations hiérarchiques               │
│      └── Niveaux: ZERO → ONE → TWO                           │
└──────────────────────────────────────────────────────────────┘
```

## 10. Recommandations

1. **Réactiver les middlewares** désactivés dans `EmployeeController` et `RoleController`

2. **Corriger l'incohérence de nommage** : remplacer `voir-un-tout` par `voir-un-all` dans tous les middlewares

3. **Documenter les permissions** dans une table de référence pour les développeurs

4. **Ajouter des tests** pour vérifier que les permissions sont correctement appliquées

5. **Considérer l'ajout de `voir-un-employee`** au rôle EMPLOYEE pour permettre la consultation de l'annuaire

---

*Document généré le 27 novembre 2025*
