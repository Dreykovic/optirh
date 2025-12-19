# 📚 Documentation Métier des Entités OPTIRH

Ce document fournit une explication métier complète de toutes les entités du système OPTIRH.

---

## Table des Matières

- [Module OptiHR - Gestion RH](#-module-optihr---gestion-des-ressources-humaines)
  - [Employé](#-employé-employee)
  - [Utilisateur](#-utilisateur-user)
  - [Direction / Service](#️-direction--service-department)
  - [Poste](#-poste-job)
  - [Affectation](#-affectation-duty)
  - [Demande d'Absence](#️-demande-dabsence-absence)
  - [Type d'Absence](#-type-dabsence-absencetype)
  - [Jour Férié](#-jour-férié-holiday)
  - [Demande de Document](#-demande-de-document-documentrequest)
  - [Type de Document](#-type-de-document-documenttype)
  - [Fichier](#-fichier-file)
  - [Publication](#-publication-publication)
  - [Formation](#-formation-training)
  - [Évaluation](#-évaluation-evaluation)
  - [Décision Annuelle](#-décision-annuelle-annualdecision)
- [Module Recours](#️-module-recours---gestion-des-recours-administratifs)
  - [Recours](#-recours-appeal)
  - [Requérant](#-requérant-applicant)
  - [Autorité](#️-autorité-authority)
  - [Commission DAC](#-commission-dac-dac)
  - [Décision](#️-décision-decision)
  - [Commentaire](#-commentaire-comment)
  - [Personnel](#-personnel-personnal)
- [Entités Système](#-entités-système)
  - [Journal d'Activité](#-journal-dactivité-activitylog)
- [Schéma des Relations](#-schéma-des-relations)
- [Processus Métier](#-synthèse-des-processus-métier)

---

## 🏢 MODULE OPTIHR - Gestion des Ressources Humaines

---

### 👤 EMPLOYÉ (Employee)

#### Définition métier

L'employé représente toute personne physique travaillant au sein de l'organisation, qu'elle soit en activité, suspendue ou ayant quitté l'entreprise.

#### Rôle dans le système

C'est l'entité centrale du module RH. Toutes les opérations (absences, documents, affectations) sont liées directement ou indirectement à un employé.

#### Informations gérées

| Catégorie | Données | Utilité métier |
|-----------|---------|----------------|
| **Identité** | Matricule, Nom, Prénom, Genre | Identification unique dans l'organisation |
| **Contact** | Email, Téléphone, Adresse | Communication et correspondance |
| **État civil** | Date de naissance, Nationalité, Situation matrimoniale, Religion | Gestion administrative et avantages sociaux |
| **Urgence** | Contact d'urgence | Sécurité au travail |
| **Bancaire** | Banque, RIB, IBAN, SWIFT | Versement des salaires |
| **Statut** | ACTIVATED, DEACTIVATED | Gestion du cycle de vie |

#### Cycle de vie

```
Création ──► ACTIVATED ──► DEACTIVATED ──► (Archivage)
                │
                └──► Peut être réactivé
```

#### Règles métier

- Le matricule est unique et ne peut être modifié après création
- Un employé doit avoir au moins une affectation active pour travailler
- Les informations bancaires sont masquées pour des raisons de confidentialité

#### Fichier source

`app/Models/OptiHr/Employee.php`

---

### 🔐 UTILISATEUR (User)

#### Définition métier

L'utilisateur représente un compte d'accès au système OPTIRH. C'est l'identité numérique permettant de se connecter et d'effectuer des opérations.

#### Rôle dans le système

Gère l'authentification, les autorisations et la traçabilité des actions dans le système.

#### Informations gérées

| Donnée | Utilité métier |
|--------|----------------|
| Username | Identifiant de connexion unique |
| Email | Communication et récupération de mot de passe |
| Password | Sécurité d'accès (hashé) |
| Profile | Type de compte (ADMIN, EMPLOYEE) |
| Status | ACTIVATED, DEACTIVATED |
| Picture | Photo de profil |

#### Lien avec l'employé

```
┌────────────────┐         ┌────────────────┐
│   EMPLOYÉ      │ 1 ── N  │  UTILISATEUR   │
│  (Pierre)      │────────►│  (pierre_rh)   │
│                │         │  (pierre_admin)│
└────────────────┘         └────────────────┘

Un employé peut avoir plusieurs comptes (ex: un compte normal + un compte admin)
```

#### Rôles disponibles

| Rôle | Description | Accès |
|------|-------------|-------|
| ADMIN | Administrateur système | Accès complet |
| GRH | Gestionnaire RH | Module OptiHR complet |
| DG | Directeur Général | Validations, tous modules |
| DSAF | Directeur Administratif | Validations absences/documents |
| EMPLOYEE | Employé standard | Ses propres demandes |
| DRAJ | Juriste | Module Recours |
| standart | Utilisateur basique | Accès recours uniquement |

#### Fichier source

`app/Models/User.php`

---

### 🏛️ DIRECTION / SERVICE (Department)

#### Définition métier

Une direction (ou service) est une unité organisationnelle regroupant plusieurs postes sous une même autorité. Elle représente la structure hiérarchique de l'organisation.

#### Rôle dans le système

Permet d'organiser les postes et employés par entité fonctionnelle, facilitant la gestion et le reporting.

#### Informations gérées

| Donnée | Utilité métier |
|--------|----------------|
| Nom | Identifiant court (DG, DSAF, DRAJ...) |
| Description | Nom complet de la direction |
| Directeur | Employé responsable de la direction |
| Status | ACTIVATED, DEACTIVATED |

#### Exemple d'organigramme

```
                    ┌─────────────────────────────┐
                    │            DG               │
                    │   Direction Générale        │
                    │   Dir: Jean DUPONT          │
                    └──────────────┬──────────────┘
                                   │
        ┌──────────────┬───────────┼───────────┬──────────────┐
        ▼              ▼           ▼           ▼              ▼
   ┌─────────┐   ┌─────────┐  ┌─────────┐ ┌─────────┐   ┌─────────┐
   │  DSAF   │   │  DRAJ   │  │   DIE   │ │  DFAT   │   │  DCRP   │
   │ Finance │   │Juridique│  │Enquêtes │ │Formation│   │ Comm.   │
   └─────────┘   └─────────┘  └─────────┘ └─────────┘   └─────────┘
```

#### Règles métier

- Chaque direction doit avoir un directeur désigné
- Une direction peut contenir plusieurs postes
- Les directions sont utilisées pour le reporting et les statistiques

#### Fichier source

`app/Models/OptiHr/Department.php`

---

### 💼 POSTE (Job)

#### Définition métier

Le poste représente une fonction ou un rôle défini dans l'organigramme de l'organisation. C'est une position qui existe indépendamment de la personne qui l'occupe.

#### Rôle dans le système

- Définit la structure hiérarchique (qui valide les demandes de qui)
- Organise les responsabilités au sein d'une direction
- Permet le suivi des effectifs par fonction

#### Informations gérées

| Donnée | Utilité métier |
|--------|----------------|
| Titre | Intitulé du poste (Chef Service RH) |
| Description | Missions et responsabilités |
| Direction | Rattachement organisationnel |
| Supérieur N+1 | Poste du supérieur hiérarchique |
| Status | ACTIVATED, DEACTIVATED |

#### Hiérarchie N+1 (Auto-référence)

```
┌──────────────────────┐
│  Directeur Général   │ ◄── N+1 = NULL (sommet)
└──────────┬───────────┘
           │
    ┌──────┴──────┐
    ▼             ▼
┌────────┐   ┌────────┐
│Dir DSAF│   │Dir DRAJ│ ◄── N+1 = DG
└───┬────┘   └────────┘
    │
    ▼
┌────────┐
│Chef RH │ ◄── N+1 = Dir DSAF
└───┬────┘
    │
    ▼
┌────────┐
│Comptable│ ◄── N+1 = Chef RH
└────────┘
```

#### Utilisation pour les validations

Quand un comptable demande un congé :
1. Le Chef RH (N+1) valide en premier
2. Le Dir DSAF (N+2) valide ensuite
3. Le DG (N+3) valide en dernier (si requis)

#### Fichier source

`app/Models/OptiHr/Job.php`

---

### 📋 AFFECTATION (Duty)

#### Définition métier

L'affectation représente l'attribution formelle d'un employé à un poste pour une période donnée. C'est le lien contractuel entre la personne et sa fonction.

#### Rôle dans le système

- Historise la carrière de chaque employé
- Gère le solde de congés
- Détermine la position hiérarchique actuelle
- Permet de calculer l'ancienneté

#### Informations gérées

| Donnée | Utilité métier |
|--------|----------------|
| Employé | Qui est affecté |
| Poste | À quelle fonction |
| Date de début | Depuis quand |
| Durée | Durée prévue (en mois) |
| Type | CDI, CDD, Stage, Intérim... |
| Solde congés | Jours de congés disponibles |
| Évolution | ON_GOING, ENDED, SUSPENDED... |
| Status | ACTIVATED, DEACTIVATED |

#### États d'évolution

```
┌─────────────┐
│  ON_GOING   │ ◄── Affectation active (poste actuel)
└──────┬──────┘
       │
       ├──────────► SUSPENDED (Suspension temporaire)
       │                 │
       │                 └──► ON_GOING (Reprise)
       │
       ├──────────► ENDED (Fin normale du contrat)
       │
       ├──────────► RESIGNED (Démission)
       │
       └──────────► DISMISSED (Licenciement)
```

#### Exemple de carrière

```
Pierre DURAND - Historique des affectations
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

2020 ├── Comptable (CDD 12 mois)
     │   └── Évolution: ENDED
     │
2021 ├── Comptable (CDI)
     │   └── Évolution: ENDED (Promotion)
     │
2023 ├── Chef Service RH (CDI)
     │   └── Évolution: ON_GOING ◄── Affectation actuelle
     │       └── Solde congés: 25 jours
```

#### Règles métier

- Un employé ne peut avoir qu'une seule affectation ON_GOING à la fois
- Le solde de congés est rattaché à l'affectation, pas à l'employé
- Chaque année, le solde est incrémenté de 30 jours (commande automatique)

#### Fichier source

`app/Models/OptiHr/Duty.php`

---

### 🏖️ DEMANDE D'ABSENCE (Absence)

#### Définition métier

Une demande d'absence est une requête formelle d'un employé pour s'absenter de son poste pendant une période donnée, pour un motif spécifique (congé, maladie, événement familial...).

#### Rôle dans le système

- Gère le workflow de validation hiérarchique
- Décompte les jours du solde de congés
- Génère les documents officiels (autorisation d'absence)
- Assure la traçabilité des absences

#### Informations gérées

| Donnée | Utilité métier |
|--------|----------------|
| Type d'absence | Catégorie (congé annuel, maladie...) |
| Date début / fin | Période d'absence |
| Nombre de jours | Durée demandée |
| Motif | Justification de la demande |
| Adresse | Où joindre l'employé pendant l'absence |
| Justificatif | Document prouvant le motif (certificat médical...) |
| Déductible | Si les jours sont déduits du solde |
| Commentaire | Remarques des validateurs |

#### Workflow de validation

```
┌─────────────────────────────────────────────────────────────────┐
│                    CIRCUIT DE VALIDATION                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Employé                                                        │
│     │                                                           │
│     ▼                                                           │
│  ┌──────────┐                                                   │
│  │ PENDING  │ Niveau ZERO - En attente                          │
│  └────┬─────┘                                                   │
│       │                                                         │
│       ▼ Validation N+1                                          │
│  ┌──────────────┐                                               │
│  │ IN_PROGRESS  │ Niveau ONE - Validé par chef direct           │
│  └────┬─────────┘                                               │
│       │                                                         │
│       ▼ Validation N+2                                          │
│  ┌──────────────┐                                               │
│  │ IN_PROGRESS  │ Niveau TWO - Validé par direction             │
│  └────┬─────────┘                                               │
│       │                                                         │
│       ▼ Validation finale                                       │
│  ┌──────────┐                                                   │
│  │ APPROVED │ Niveau THREE - Validé + Numéro attribué           │
│  └──────────┘                                                   │
│                                                                 │
│  À tout moment:                                                 │
│  ┌──────────┐     ┌───────────┐                                 │
│  │ REJECTED │     │ CANCELLED │ (par l'employé)                 │
│  └──────────┘     └───────────┘                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### Règles métier

- Une absence approuvée reçoit un numéro séquentiel unique
- Les jours déductibles sont soustraits du solde de congés
- L'employé peut annuler sa demande tant qu'elle n'est pas approuvée
- Un justificatif peut être exigé selon le type d'absence

#### Fichier source

`app/Models/OptiHr/Absence.php`

---

### 📂 TYPE D'ABSENCE (AbsenceType)

#### Définition métier

Le type d'absence catégorise les différentes raisons pour lesquelles un employé peut s'absenter. Chaque type a ses propres règles de déductibilité et de justification.

#### Types courants

| Type | Déductible | Justificatif | Description |
|------|------------|--------------|-------------|
| Congé annuel | ✅ Oui | Non requis | Congés payés annuels |
| Congé maladie | ❌ Non | Certificat médical | Arrêt pour raison de santé |
| Congé maternité | ❌ Non | Certificat | Congé avant/après accouchement |
| Congé paternité | ❌ Non | Acte de naissance | Naissance d'un enfant |
| Congé mariage | ❌ Non | Acte de mariage | Mariage de l'employé |
| Congé décès | ❌ Non | Acte de décès | Décès d'un proche |
| Permission exceptionnelle | Selon cas | Selon cas | Autorisation spéciale |

#### Fichier source

`app/Models/OptiHr/AbsenceType.php`

---

### 📅 JOUR FÉRIÉ (Holiday)

#### Définition métier

Les jours fériés sont les dates officielles de repos national ou institutionnel. Ils sont exclus du calcul des jours ouvrés pour les absences.

#### Utilisation

- Calcul automatique des jours ouvrés dans une période d'absence
- Affichage dans le calendrier des absences
- Planning des ressources

#### Fichier source

`app/Models/OptiHr/Holiday.php`

---

### 📄 DEMANDE DE DOCUMENT (DocumentRequest)

#### Définition métier

Une demande de document est une requête formelle d'un employé pour obtenir un document administratif officiel (attestation de travail, certificat de salaire, etc.).

#### Rôle dans le système

- Gère le workflow de validation
- Génère automatiquement les documents PDF
- Attribue un numéro de référence unique
- Historise les demandes

#### Workflow simplifié

```
Employé ──► PENDING ──► IN_PROGRESS ──► APPROVED
                            │              │
                            │              └──► Document généré avec numéro
                            │
                            └──► REJECTED (avec motif)
```

#### Fichier source

`app/Models/OptiHr/DocumentRequest.php`

---

### 📂 TYPE DE DOCUMENT (DocumentType)

#### Définition métier

Catégorise les différents documents administratifs que l'organisation peut délivrer à ses employés.

#### Types courants

| Type | Description | Délai usuel |
|------|-------------|-------------|
| Attestation de travail | Prouve l'emploi actuel | 24-48h |
| Certificat de travail | Récapitulatif de carrière | 48-72h |
| Attestation de salaire | Justifie les revenus | 24-48h |
| Attestation de congé | Confirme une période de congé | 24h |

#### Fichier source

`app/Models/OptiHr/DocumentType.php`

---

### 📁 FICHIER (File)

#### Définition métier

Un fichier est un document numérique stocké dans le dossier personnel d'un employé (CV, diplômes, contrats, pièces d'identité, bulletins de paie...).

#### Informations gérées

| Donnée | Utilité |
|--------|---------|
| Nom | Nom du fichier |
| Nom d'affichage | Libellé lisible |
| Type MIME | Format du fichier |
| Chemin | Emplacement de stockage |
| Date d'upload | Traçabilité |

#### Fichier source

`app/Models/OptiHr/File.php`

---

### 📢 PUBLICATION (Publication)

#### Définition métier

Une publication est une communication interne diffusée à l'ensemble ou une partie des employés (annonces, notes de service, informations générales...).

#### Informations gérées

| Donnée | Utilité |
|--------|---------|
| Titre | Sujet de la publication |
| Contenu | Corps du message |
| Auteur | Qui publie |
| Date de publication | Quand |
| Statut | DRAFT, PUBLISHED, ARCHIVED |
| Pièces jointes | Documents associés |

#### Fichier source

`app/Models/OptiHr/Publication.php`

---

### 📎 PIÈCE JOINTE PUBLICATION (PublicationFile)

#### Définition métier

Document attaché à une publication pour compléter l'information (PDF, images, présentations...).

#### Fichier source

`app/Models/OptiHr/PublicationFile.php`

---

### 🎓 FORMATION (Training)

#### Définition métier

Une formation représente un programme de développement des compétences suivi par un employé dans le cadre de son affectation.

#### Informations gérées

| Donnée | Utilité |
|--------|---------|
| Titre | Intitulé de la formation |
| Problématique | Besoin identifié |
| Compétences à acquérir | Objectifs pédagogiques |
| Indicateurs de succès | Critères d'évaluation |
| Méthode d'exécution | Présentiel, e-learning... |
| Période | Dates de réalisation |
| Observation supérieur | Avis du N+1 |

#### Fichier source

`app/Models/OptiHr/Training.php`

---

### 📊 ÉVALUATION (Evaluation)

#### Définition métier

L'évaluation est l'appréciation formelle de la performance d'un employé sur une période donnée, généralement annuelle.

#### Fichier source

`app/Models/OptiHr/Evaluation.php`

---

### 📜 DÉCISION ANNUELLE (AnnualDecision)

#### Définition métier

Document officiel fixant les règles et paramètres RH pour une année donnée (grilles salariales, jours de congés, primes...).

#### Fichier source

`app/Models/OptiHr/AnnualDecision.php`

---

## ⚖️ MODULE RECOURS - Gestion des Recours Administratifs

---

### 📝 RECOURS (Appeal)

#### Définition métier

Un recours est une contestation formelle déposée par un contribuable ou une entreprise contre une décision administrative (fiscale, douanière...).

#### Rôle dans le système

- Enregistrement et suivi des recours
- Gestion des délais légaux
- Workflow de traitement
- Génération des notifications

#### Informations gérées

| Donnée | Utilité métier |
|--------|----------------|
| Date de dépôt | Début du délai légal |
| Heure de dépôt | Précision pour les délais |
| Type | Catégorie du recours |
| Objet | Description de la contestation |
| Compteur de jours | Suivi du délai de traitement |
| Statut d'analyse | État du traitement |
| Requérant | Qui dépose |
| Autorité | Administration concernée |
| Commission DAC | Instance de décision |

#### États du recours

```
┌─────────────────────────────────────────────────────────────────┐
│                    CYCLE DE VIE D'UN RECOURS                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Dépôt ──► EN_COURS ──► ANALYSÉ ──► DÉCIDÉ                     │
│                │            │          │                        │
│                │            │          ├──► ACCEPTÉ             │
│                │            │          ├──► REJETÉ              │
│                │            │          └──► PARTIELLEMENT       │
│                │            │                                   │
│                │            └──► SUSPENDU (en attente info)     │
│                │                    │                           │
│                │                    └──► Reprise possible       │
│                │                                                │
│                └──► CRD (Classé sans suite)                     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### Règles métier

- Le compteur de jours s'incrémente automatiquement (commande horaire)
- Alerte à 5 jours pour les recours en analyse
- Alerte à 13 jours pour les recours suspendus
- Chaque action est tracée avec l'agent responsable

#### Fichier source

`app/Models/Recours/Appeal.php`

---

### 👤 REQUÉRANT (Applicant)

#### Définition métier

Le requérant est la personne physique ou morale qui dépose un recours administratif. C'est le demandeur dans la procédure.

#### Informations gérées

| Donnée | Utilité métier |
|--------|----------------|
| Nom | Identité du requérant |
| Adresse | Adresse postale |
| Téléphone | Contact téléphonique |
| Email | Contact électronique |
| NIF | Numéro d'Identification Fiscale |

#### Règle métier

- Un requérant peut déposer plusieurs recours
- Le NIF permet d'identifier les entreprises

#### Fichier source

`app/Models/Recours/Applicant.php`

---

### 🏛️ AUTORITÉ (Authority)

#### Définition métier

L'autorité est l'administration ou l'organisme dont la décision est contestée par le recours.

#### Exemples

- Direction des Impôts
- Direction des Douanes
- Commune de...
- Ministère de...

#### Fichier source

`app/Models/Recours/Authority.php`

---

### 👥 COMMISSION DAC (Dac)

#### Définition métier

La Commission DAC (Commission de Décision Administrative Contentieuse) est l'instance collégiale chargée d'examiner et de statuer sur les recours.

#### Informations gérées

| Donnée | Utilité |
|--------|---------|
| Référence | Numéro de la commission |
| Objet | Thème de la session |
| AC | Référence de l'acte |

#### Fichier source

`app/Models/Recours/Dac.php`

---

### ⚖️ DÉCISION (Decision)

#### Définition métier

La décision est le verdict rendu sur un recours, pouvant être favorable, défavorable ou partiellement favorable au requérant.

#### Informations gérées

| Donnée | Utilité |
|--------|---------|
| Décision | Texte de la décision |
| Date | Date du prononcé |
| Référence rejet | N° si rejeté |
| Référence suspension | N° si suspendu |
| Référence décision | N° de la décision finale |
| Fichiers | Documents officiels (PDF) |

#### Fichier source

`app/Models/Recours/Decision.php`

---

### 💬 COMMENTAIRE (Comment)

#### Définition métier

Un commentaire est une note ajoutée par un agent au dossier d'un recours pour documenter le traitement, poser des questions ou transmettre des informations.

#### Utilisation

- Suivi interne du dossier
- Communication entre agents
- Historique des échanges

#### Fichier source

`app/Models/Recours/Comment.php`

---

### 👨‍💼 PERSONNEL (Personnal)

#### Définition métier

Le personnel représente les agents administratifs qui traitent les recours. C'est l'équivalent de l'employé mais spécifique au module Recours.

#### Informations gérées

| Donnée | Utilité |
|--------|---------|
| Nom / Prénom | Identité |
| Email | Contact |
| Poste | Fonction |
| Sexe | Civilité |

#### Note

Cette entité est séparée de Employee car le module Recours peut fonctionner indépendamment du module OptiHR.

#### Fichier source

`app/Models/Recours/Personnal.php`

---

## 🔧 ENTITÉS SYSTÈME

---

### 📋 JOURNAL D'ACTIVITÉ (ActivityLog)

#### Définition métier

Le journal d'activité enregistre automatiquement toutes les actions effectuées dans le système pour assurer la traçabilité et l'audit.

#### Informations enregistrées

| Donnée | Utilité |
|--------|---------|
| Utilisateur | Qui a fait l'action |
| Action | Type d'opération (CREATE, UPDATE, DELETE...) |
| Modèle concerné | Quelle entité |
| ID du modèle | Quel enregistrement |
| Anciennes valeurs | État avant modification |
| Nouvelles valeurs | État après modification |
| Adresse IP | D'où vient l'action |
| User Agent | Quel navigateur/appareil |
| Date/Heure | Quand |

#### Utilisation

- Audit de sécurité
- Investigation en cas de problème
- Conformité réglementaire
- Historique des modifications

#### Rétention

Les logs sont automatiquement supprimés après 90 jours (tâche planifiée hebdomadaire).

#### Fichier source

`app/Models/ActivityLog.php`

---

## 📊 SCHÉMA DES RELATIONS

### Module OptiHR - Relations principales

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              HIÉRARCHIE ORGANISATIONNELLE                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌───────────┐    ┌─────────┐    ┌────────────┐    ┌───────────┐          │
│   │ Direction │───►│  Poste  │───►│ Affectation│───►│  Employé  │          │
│   └─────┬─────┘    └────┬────┘    └─────┬──────┘    └─────┬─────┘          │
│         │               │               │                 │                 │
│    directeur       supérieur N+1    ┌───┴───┐        ┌────┴────┐           │
│         │               │           │       │        │         │           │
│         ▼               ▼           ▼       ▼        ▼         ▼           │
│   ┌───────────┐    ┌─────────┐  Absence  Formation  Fichier  Utilisateur   │
│   │  Employé  │    │  Poste  │  Document  Évaluation                       │
│   └───────────┘    └─────────┘                                             │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Module Recours - Relations principales

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                             MODULE RECOURS                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌───────────┐    ┌─────────┐    ┌────────────┐    ┌───────────┐          │
│   │ Requérant │───►│ Recours │◄───│ Commission │    │  Décision │          │
│   └───────────┘    └────┬────┘    └────────────┘    └─────┬─────┘          │
│                         │                                 │                 │
│                    ┌────┼────┐                            │                 │
│                    │    │    │                            │                 │
│                    ▼    ▼    ▼                            │                 │
│              Autorité Commentaire◄────────────────────────┘                 │
│                         │                                                   │
│                         ▼                                                   │
│                    ┌─────────┐                                              │
│                    │Personnel│                                              │
│                    └─────────┘                                              │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Tableau récapitulatif des relations

| Entité | Relation | Vers | Type | Description |
|--------|----------|------|------|-------------|
| **Utilisateur** | appartient à | Employé | N-1 | Un utilisateur est lié à un employé |
| **Employé** | possède | Utilisateur | 1-N | Un employé peut avoir plusieurs comptes |
| **Employé** | a eu | Affectation | 1-N | Historique des postes occupés |
| **Employé** | possède | Fichier | 1-N | Documents personnels |
| **Direction** | dirigée par | Employé | N-1 | Le directeur de la direction |
| **Direction** | contient | Poste | 1-N | Postes rattachés |
| **Poste** | appartient à | Direction | N-1 | Rattachement organisationnel |
| **Poste** | a pour supérieur | Poste | N-1 | Hiérarchie N+1 |
| **Affectation** | concerne | Employé | N-1 | L'employé affecté |
| **Affectation** | au | Poste | N-1 | Le poste occupé |
| **Affectation** | comprend | Absence | 1-N | Demandes d'absence |
| **Absence** | de type | Type d'absence | N-1 | Catégorie |
| **Document** | de type | Type de document | N-1 | Catégorie |
| **Publication** | créée par | Utilisateur | N-1 | Auteur |
| **Recours** | déposé par | Requérant | N-1 | Demandeur |
| **Recours** | traité par | Commission | N-1 | Instance de décision |
| **Recours** | a des | Commentaire | 1-N | Suivi du dossier |

---

## 📋 SYNTHÈSE DES PROCESSUS MÉTIER

### Processus d'embauche

```
1. Créer l'Employé (informations personnelles)
2. Créer le Poste si nécessaire (dans une Direction)
3. Créer l'Affectation (lier Employé au Poste)
4. Créer l'Utilisateur (compte de connexion)
5. Envoyer les identifiants par email
```

### Processus de demande de congé

```
1. Employé soumet une Demande d'Absence
2. N+1 valide ou rejette (niveau 1)
3. N+2 valide ou rejette (niveau 2)
4. RH/Direction valide finalement (niveau 3)
5. Numéro attribué, solde déduit, document généré
```

### Processus de demande de document

```
1. Employé soumet une Demande de Document
2. N+1 valide ou rejette (niveau 1)
3. RH valide finalement (niveau 2)
4. Numéro attribué, document PDF généré
```

### Processus de traitement de recours

```
1. Requérant dépose un Recours
2. Agent enregistre et assigne à une Commission
3. Analyse du dossier (compteur de jours actif)
4. Commission délibère
5. Décision rendue et notifiée
```

---

## 📚 LÉGENDE

| Notation | Signification |
|----------|---------------|
| **1-N** | Un vers plusieurs |
| **N-1** | Plusieurs vers un |
| **1-1** | Un vers un |
| **N+1** | Supérieur hiérarchique direct |
| **N+2** | Supérieur du supérieur |

---

*Document généré pour OPTIRH - Système de Gestion des Ressources Humaines*
