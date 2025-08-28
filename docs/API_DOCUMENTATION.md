# Documentation API - OPTIRH

## Table des matières
1. [Introduction](#introduction)
2. [Authentification](#authentification)
3. [Structure des Réponses](#structure-des-réponses)
4. [Codes d'Erreur](#codes-derreur)
5. [Endpoints Authentification](#endpoints-authentification)
6. [Endpoints OptiHR](#endpoints-optihr)
7. [Endpoints Recours](#endpoints-recours)
8. [Webhooks](#webhooks)
9. [Rate Limiting](#rate-limiting)
10. [Exemples d'Utilisation](#exemples-dutilisation)

## Introduction

L'API OPTIRH fournit un accès programmatique aux fonctionnalités de gestion des ressources humaines. Cette API RESTful utilise JSON pour les échanges de données et suit les standards HTTP.

### URL de Base
```
Production: https://optirh.votre-domaine.com/api
Staging: https://staging-optirh.votre-domaine.com/api
```

### Versions
- **Version actuelle** : v1
- **Versions supportées** : v1
- **Format d'URL** : `/api/v1/{endpoint}`

## Authentification

### Laravel Sanctum
L'API utilise Laravel Sanctum pour l'authentification basée sur des tokens.

#### Obtention d'un Token
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "utilisateur@example.com",
    "password": "mot_de_passe"
}
```

#### Réponse
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "1|abc123xyz789token",
        "expires_at": "2024-12-31T23:59:59Z"
    }
}
```

#### Utilisation du Token
```http
GET /api/v1/employees
Authorization: Bearer 1|abc123xyz789token
Accept: application/json
```

### Révocation de Token
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

## Structure des Réponses

### Réponse de Succès
```json
{
    "success": true,
    "data": {
        // Données demandées
    },
    "message": "Opération réussie",
    "meta": {
        "timestamp": "2024-01-15T10:30:00Z",
        "version": "1.0"
    }
}
```

### Réponse avec Pagination
```json
{
    "success": true,
    "data": [
        // Liste des éléments
    ],
    "meta": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 15,
        "total": 150,
        "from": 1,
        "to": 15
    },
    "links": {
        "first": "/api/v1/employees?page=1",
        "last": "/api/v1/employees?page=10",
        "prev": null,
        "next": "/api/v1/employees?page=2"
    }
}
```

### Réponse d'Erreur
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Les données fournies sont invalides",
        "details": {
            "email": ["Le format de l'email est invalide"],
            "password": ["Le mot de passe est requis"]
        }
    },
    "meta": {
        "timestamp": "2024-01-15T10:30:00Z",
        "request_id": "req_123456789"
    }
}
```

## Codes d'Erreur

### Codes HTTP Standards
- `200` - Succès
- `201` - Créé avec succès
- `204` - Supprimé avec succès
- `400` - Requête invalide
- `401` - Non authentifié
- `403` - Non autorisé
- `404` - Ressource non trouvée
- `422` - Données non valides
- `429` - Trop de requêtes
- `500` - Erreur serveur

### Codes d'Erreur Personnalisés
```json
{
    "VALIDATION_ERROR": "Erreur de validation des données",
    "RESOURCE_NOT_FOUND": "Ressource non trouvée",
    "PERMISSION_DENIED": "Permission refusée",
    "EMPLOYEE_NOT_ACTIVE": "Employé non actif",
    "ABSENCE_CONFLICT": "Conflit avec une absence existante",
    "INSUFFICIENT_BALANCE": "Solde de congés insuffisant",
    "DOCUMENT_PROCESSING_ERROR": "Erreur lors du traitement du document"
}
```

## Endpoints Authentification

### Login
```http
POST /api/auth/login
```

**Paramètres**
```json
{
    "email": "string (required)",
    "password": "string (required)",
    "remember": "boolean (optional)"
}
```

### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

### Refresh Token
```http
POST /api/auth/refresh
Authorization: Bearer {token}
```

### Profile Utilisateur
```http
GET /api/auth/user
Authorization: Bearer {token}
```

## Endpoints OptiHR

### Employés

#### Lister les employés
```http
GET /api/v1/employees
Authorization: Bearer {token}
```

**Paramètres de Requête**
- `page` : Numéro de page (défaut: 1)
- `per_page` : Éléments par page (défaut: 15, max: 100)
- `status` : Filtrer par statut (ACTIVATED, DEACTIVATED)
- `department_id` : Filtrer par département
- `search` : Recherche par nom, prénom, matricule

**Exemple de Réponse**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "matricule": "EMP001",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@company.com",
            "status": "ACTIVATED",
            "department": {
                "id": 1,
                "name": "IT Department"
            },
            "job": {
                "id": 1,
                "title": "Developer"
            },
            "created_at": "2024-01-15T10:00:00Z"
        }
    ]
}
```

#### Obtenir un employé
```http
GET /api/v1/employees/{id}
Authorization: Bearer {token}
```

#### Créer un employé
```http
POST /api/v1/employees
Authorization: Bearer {token}
Content-Type: application/json
```

**Paramètres**
```json
{
    "first_name": "string (required)",
    "last_name": "string (required)",
    "email": "string (required)",
    "phone_number": "string",
    "birth_date": "date",
    "gender": "M|F",
    "address1": "string",
    "city": "string",
    "country": "string",
    "department_id": "integer (required)",
    "job_id": "integer (required)",
    "bank_name": "string",
    "rib": "string"
}
```

#### Mettre à jour un employé
```http
PUT /api/v1/employees/{id}
Authorization: Bearer {token}
```

#### Supprimer un employé
```http
DELETE /api/v1/employees/{id}
Authorization: Bearer {token}
```

### Départements

#### Lister les départements
```http
GET /api/v1/departments
Authorization: Bearer {token}
```

#### Obtenir un département
```http
GET /api/v1/departments/{id}
Authorization: Bearer {token}
```

### Absences

#### Lister les absences
```http
GET /api/v1/absences
Authorization: Bearer {token}
```

**Paramètres de Requête**
- `employee_id` : Filtrer par employé
- `status` : PENDING, APPROVED, REJECTED, CANCELLED
- `start_date` : Date de début (YYYY-MM-DD)
- `end_date` : Date de fin (YYYY-MM-DD)
- `absence_type_id` : Type d'absence

#### Créer une demande d'absence
```http
POST /api/v1/absences
Authorization: Bearer {token}
```

**Paramètres**
```json
{
    "absence_type_id": "integer (required)",
    "start_date": "date (required)",
    "end_date": "date (required)",
    "reasons": "string (required)",
    "address": "string",
    "proof": "file (optional)"
}
```

#### Approuver/Rejeter une absence
```http
PATCH /api/v1/absences/{id}/status
Authorization: Bearer {token}
```

**Paramètres**
```json
{
    "status": "APPROVED|REJECTED",
    "comment": "string (optional)"
}
```

### Types d'Absences

#### Lister les types d'absences
```http
GET /api/v1/absence-types
Authorization: Bearer {token}
```

**Réponse**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Congé annuel",
            "code": "ANNUAL",
            "max_days": 30,
            "requires_proof": false,
            "is_paid": true,
            "description": "Congés payés annuels"
        }
    ]
}
```

### Demandes de Documents

#### Lister les demandes
```http
GET /api/v1/document-requests
Authorization: Bearer {token}
```

#### Créer une demande
```http
POST /api/v1/document-requests
Authorization: Bearer {token}
```

**Paramètres**
```json
{
    "document_type_id": "integer (required)",
    "purpose": "string (required)",
    "additional_info": "string (optional)"
}
```

### Publications

#### Lister les publications
```http
GET /api/v1/publications
Authorization: Bearer {token}
```

**Paramètres de Requête**
- `category` : Catégorie de publication
- `published_only` : true pour les publications actives uniquement

## Endpoints Recours

### Recours

#### Lister les recours
```http
GET /api/v1/appeals
Authorization: Bearer {token}
```

#### Créer un recours
```http
POST /api/v1/appeals
Authorization: Bearer {token}
```

**Paramètres**
```json
{
    "applicant_id": "integer (required)",
    "authority_id": "integer (required)",
    "subject": "string (required)",
    "description": "text (required)",
    "appeal_type": "string (required)",
    "documents": "array of files (optional)"
}
```

#### Mettre à jour le statut d'un recours
```http
PATCH /api/v1/appeals/{id}/status
Authorization: Bearer {token}
```

**Paramètres**
```json
{
    "status": "SUBMITTED|UNDER_REVIEW|SUSPENDED|ANALYSED|DECIDED|CLOSED",
    "comment": "string (optional)"
}
```

### Commentaires sur Recours

#### Ajouter un commentaire
```http
POST /api/v1/appeals/{appeal_id}/comments
Authorization: Bearer {token}
```

**Paramètres**
```json
{
    "content": "text (required)",
    "is_internal": "boolean (optional, default: false)"
}
```

## Webhooks

### Configuration des Webhooks
Les webhooks permettent de recevoir des notifications en temps réel lors d'événements spécifiques.

#### Événements Disponibles
- `employee.created` : Nouvel employé créé
- `employee.updated` : Employé modifié
- `absence.submitted` : Nouvelle demande d'absence
- `absence.approved` : Absence approuvée
- `absence.rejected` : Absence rejetée
- `document.requested` : Nouvelle demande de document
- `appeal.submitted` : Nouveau recours soumis
- `appeal.status_changed` : Changement de statut d'un recours

#### Structure du Payload
```json
{
    "event": "absence.approved",
    "timestamp": "2024-01-15T10:30:00Z",
    "data": {
        "absence": {
            "id": 123,
            "employee_id": 45,
            "status": "APPROVED"
        }
    },
    "webhook_id": "wh_123456"
}
```

### Configuration
```http
POST /api/v1/webhooks
Authorization: Bearer {token}
```

**Paramètres**
```json
{
    "url": "https://votre-app.com/webhooks/optirh",
    "events": ["absence.approved", "employee.created"],
    "secret": "votre_secret_webhook"
}
```

## Rate Limiting

### Limites par Défaut
- **Authentifié** : 1000 requêtes par heure
- **Non authentifié** : 100 requêtes par heure
- **Endpoints sensibles** : 60 requêtes par heure

### Headers de Rate Limiting
```http
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1640995200
```

### Dépassement de Limite
```json
{
    "success": false,
    "error": {
        "code": "RATE_LIMIT_EXCEEDED",
        "message": "Trop de requêtes. Réessayez dans 60 minutes."
    }
}
```

## Exemples d'Utilisation

### JavaScript/Fetch
```javascript
// Configuration de base
const API_BASE = 'https://optirh.votre-domaine.com/api/v1';
const TOKEN = 'your-bearer-token';

// Fonction helper pour les requêtes
async function apiRequest(endpoint, options = {}) {
    const config = {
        headers: {
            'Authorization': `Bearer ${TOKEN}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...options.headers
        },
        ...options
    };

    const response = await fetch(`${API_BASE}${endpoint}`, config);
    
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    return response.json();
}

// Lister les employés
async function getEmployees(page = 1) {
    try {
        const data = await apiRequest(`/employees?page=${page}`);
        return data.data;
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// Créer un employé
async function createEmployee(employeeData) {
    try {
        const data = await apiRequest('/employees', {
            method: 'POST',
            body: JSON.stringify(employeeData)
        });
        return data.data;
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// Créer une demande d'absence
async function createAbsenceRequest(absenceData) {
    try {
        const data = await apiRequest('/absences', {
            method: 'POST',
            body: JSON.stringify(absenceData)
        });
        return data.data;
    } catch (error) {
        console.error('Erreur:', error);
    }
}
```

### PHP/Guzzle
```php
<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OptirHrApiClient
{
    private $client;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->token = $token;
        $this->client = new Client([
            'base_uri' => $baseUrl . '/api/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function getEmployees($page = 1, $filters = [])
    {
        try {
            $response = $this->client->get('employees', [
                'query' => array_merge(['page' => $page], $filters)
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception('API Error: ' . $e->getMessage());
        }
    }

    public function createEmployee($data)
    {
        try {
            $response = $this->client->post('employees', [
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception('API Error: ' . $e->getMessage());
        }
    }

    public function createAbsenceRequest($data)
    {
        try {
            $response = $this->client->post('absences', [
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception('API Error: ' . $e->getMessage());
        }
    }
}

// Utilisation
$api = new OptirHrApiClient('https://optirh.votre-domaine.com', 'your-token');

// Lister les employés
$employees = $api->getEmployees(1, ['status' => 'ACTIVATED']);

// Créer un employé
$newEmployee = $api->createEmployee([
    'first_name' => 'Jean',
    'last_name' => 'Dupont',
    'email' => 'jean.dupont@company.com',
    'department_id' => 1,
    'job_id' => 1
]);
```

### Python/Requests
```python
import requests
from typing import Dict, List, Optional

class OptirHrApiClient:
    def __init__(self, base_url: str, token: str):
        self.base_url = f"{base_url}/api/v1"
        self.session = requests.Session()
        self.session.headers.update({
            'Authorization': f'Bearer {token}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        })

    def get_employees(self, page: int = 1, **filters) -> Dict:
        """Récupérer la liste des employés"""
        params = {'page': page, **filters}
        response = self.session.get(f"{self.base_url}/employees", params=params)
        response.raise_for_status()
        return response.json()

    def create_employee(self, data: Dict) -> Dict:
        """Créer un nouvel employé"""
        response = self.session.post(f"{self.base_url}/employees", json=data)
        response.raise_for_status()
        return response.json()

    def create_absence_request(self, data: Dict) -> Dict:
        """Créer une demande d'absence"""
        response = self.session.post(f"{self.base_url}/absences", json=data)
        response.raise_for_status()
        return response.json()

    def get_absence_types(self) -> List[Dict]:
        """Récupérer les types d'absences"""
        response = self.session.get(f"{self.base_url}/absence-types")
        response.raise_for_status()
        return response.json()['data']

# Utilisation
client = OptirHrApiClient('https://optirh.votre-domaine.com', 'your-token')

# Lister les employés actifs
employees = client.get_employees(status='ACTIVATED')

# Créer un employé
new_employee = client.create_employee({
    'first_name': 'Marie',
    'last_name': 'Martin',
    'email': 'marie.martin@company.com',
    'department_id': 2,
    'job_id': 3
})

# Créer une demande d'absence
absence_request = client.create_absence_request({
    'absence_type_id': 1,
    'start_date': '2024-02-15',
    'end_date': '2024-02-20',
    'reasons': 'Congés annuels'
})
```

## Support et Contact

### Documentation Supplémentaire
- **Postman Collection** : [Lien vers la collection Postman]
- **OpenAPI Spec** : `/api/documentation` (Swagger UI)
- **SDKs** : Disponibles pour JavaScript, PHP, Python

### Support Technique
- **Email** : api-support@optirh.com
- **Documentation** : https://docs.optirh.com
- **Status Page** : https://status.optirh.com

### Changelog
- **v1.0** : Version initiale avec endpoints de base
- **v1.1** : Ajout des webhooks et amélioration de la pagination
- **v1.2** : Support des uploads de fichiers pour les absences

---

*Documentation API OPTIRH - Version 1.2*