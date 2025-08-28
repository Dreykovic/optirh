# Manuel Utilisateur - OPTIRH

## Table des matières
1. [Introduction](#introduction)
2. [Connexion et Navigation](#connexion-et-navigation)
3. [Module OptiHR - Gestion RH](#module-optihr---gestion-rh)
4. [Module Recours - Gestion des Recours](#module-recours---gestion-des-recours)
5. [Gestion des Utilisateurs](#gestion-des-utilisateurs)
6. [Rapports et Statistiques](#rapports-et-statistiques)
7. [FAQ](#faq)

## Introduction

OPTIRH est une plateforme complète de gestion des ressources humaines permettant de gérer les employés, les absences, les demandes de documents et les recours administratifs.

### Fonctionnalités Principales
- **Gestion du Personnel** : Création et gestion des profils d'employés
- **Gestion des Absences** : Demandes de congés, validation hiérarchique
- **Demandes de Documents** : Attestations, certificats, etc.
- **Recours Administratifs** : Traitement des recours disciplinaires
- **Tableaux de Bord** : Statistiques et indicateurs RH
- **Publications** : Gestion des annonces et communications

## Connexion et Navigation

### Première Connexion
1. Accédez à l'URL de l'application OPTIRH
2. Saisissez vos identifiants fournis par l'administrateur
3. Changez votre mot de passe lors de la première connexion

### Interface Principale
L'interface est organisée en modules :
- **Gateway** : Page d'accueil avec choix du module
- **OptiHR** : Module de gestion RH
- **Recours** : Module de gestion des recours

### Navigation
- Menu latéral avec sections principales
- Fil d'Ariane pour la navigation
- Notifications en temps réel
- Menu utilisateur (profil, déconnexion)

## Module OptiHR - Gestion RH

### 1. Tableau de Bord RH

#### Indicateurs Clés
- **Nombre d'employés actifs**
- **Demandes d'absences en attente**
- **Demandes de documents en cours**
- **Statistiques mensuelles**

#### Widgets Disponibles
- Graphiques d'évolution des effectifs
- Répartition par départements
- Absences récentes
- Documents en cours de traitement

### 2. Gestion du Personnel

#### 2.1 Liste des Employés
**Accès** : OptiHR → Personnel → Membres

**Fonctionnalités** :
- Vue d'ensemble de tous les employés
- Filtres par statut, département, poste
- Recherche par nom, matricule
- Export en PDF/Excel

**Colonnes affichées** :
- Matricule
- Nom complet
- Département
- Poste
- Statut (Actif/Inactif)
- Actions (Voir/Modifier/Supprimer)

#### 2.2 Création d'un Employé
**Accès** : Personnel → Membres → Nouveau

**Informations Requises** :

**Identité** :
- Prénom et nom
- Date de naissance
- Nationalité
- État civil
- Sexe

**Contact** :
- Adresse complète
- Numéro de téléphone
- Email professionnel

**Informations Professionnelles** :
- Matricule (généré automatiquement)
- Département
- Poste
- Date d'embauche
- Statut d'emploi

**Informations Bancaires** :
- Nom de la banque
- Code banque et guichet
- RIB/IBAN
- Code SWIFT

#### 2.3 Modification d'un Employé
**Accès** : Personnel → Membres → Actions → Modifier

Sections modifiables :
- **Informations personnelles**
- **Informations professionnelles**
- **Informations bancaires**

### 3. Gestion des Absences

#### 3.1 Types d'Absences
**Accès** : OptiHR → Présences → Types

**Types Disponibles** :
- **Congé annuel** : Congés payés légaux
- **Congé maladie** : Arrêt maladie avec justificatif
- **Congé maternité/paternité** : Congés familiaux
- **Permission exceptionnelle** : Absences courtes justifiées
- **Congé sans solde** : Absence non rémunérée

Chaque type a :
- Durée maximale autorisée
- Conditions d'attribution
- Pièces justificatives requises
- Impact sur la rémunération

#### 3.2 Demande d'Absence
**Accès** : Présences → Absences → Nouvelle demande

**Processus de Demande** :

1. **Sélection du type d'absence**
2. **Informations de la demande** :
   - Date de début
   - Date de fin
   - Nombre de jours
   - Motif détaillé
   - Adresse pendant l'absence

3. **Pièces justificatives** (si requises) :
   - Upload de documents (PDF, images)
   - Certificats médicaux
   - Justificatifs officiels

4. **Validation** :
   - Vérification automatique des soldes
   - Contrôle des chevauchements
   - Soumission pour validation

#### 3.3 Validation des Absences

**Circuit de Validation** :
1. **Niveau 1** : Chef direct
2. **Niveau 2** : Directeur de département  
3. **Niveau 3** : Direction RH (si requis)

**Actions Possibles** :
- **Approuver** : Accepter la demande
- **Rejeter** : Refuser avec motif
- **Demander des précisions** : Retour à l'employé

#### 3.4 Suivi des Absences
**Accès** : Présences → Absences → Liste

**Filtres Disponibles** :
- Par statut (En attente, Approuvées, Rejetées)
- Par période
- Par employé/département
- Par type d'absence

**Statuts d'Absence** :
- `PENDING` : En attente de validation
- `APPROVED` : Validée par la hiérarchie
- `REJECTED` : Rejetée
- `CANCELLED` : Annulée par l'employé

### 4. Gestion des Documents

#### 4.1 Types de Documents
**Accès** : OptiHR → Documents → Types

**Documents Disponibles** :
- **Attestation de travail**
- **Certificat de salaire**
- **Attestation de stage**
- **Certificat de cessation de service**
- **Relevé de carrière**

#### 4.2 Demande de Document
**Accès** : Documents → Nouvelle demande

**Processus** :
1. Sélection du type de document
2. Spécification des détails (période, objet)
3. Justification de la demande
4. Soumission

#### 4.3 Traitement des Demandes
**Niveaux de traitement** :
- **Service RH** : Préparation du document
- **Direction** : Validation et signature
- **Émission** : Mise à disposition

### 5. Publications et Annonces

#### 5.1 Consultation des Publications
**Accès** : OptiHR → Publications

**Types de Publications** :
- Annonces officielles
- Circulaires internes
- Avis de concours
- Informations générales

#### 5.2 Gestion des Publications (Administrateurs)
**Fonctionnalités** :
- Création de publications
- Catégorisation
- Ciblage par département
- Planification de publication
- Gestion des pièces jointes

### 6. Congés et Jours Fériés

#### 6.1 Calendrier des Jours Fériés
**Accès** : Présences → Jours fériés

**Fonctionnalités** :
- Consultation du calendrier annuel
- Jours fériés fixes et mobiles
- Impact sur les plannings

#### 6.2 Décisions Annuelles
**Accès** : Présences → Décisions annuelles

Gestion des :
- Calendrier des congés collectifs
- Fermetures exceptionnelles
- Aménagements d'horaires

## Module Recours - Gestion des Recours

### 1. Tableau de Bord Recours

**Indicateurs** :
- Recours en cours de traitement
- Délais de traitement moyens
- Taux de recours acceptés/rejetés
- Statistiques par type de recours

### 2. Soumission d'un Recours

#### 2.1 Nouveau Recours
**Accès** : Recours → Nouveau

**Informations Requises** :
- **Demandeur** : Informations personnelles
- **Autorité concernée** : Service ou personne visée
- **Objet du recours** : Nature de la décision contestée
- **Motifs** : Arguments et justifications
- **Pièces jointes** : Documents de preuve

#### 2.2 Types de Recours
- **Recours disciplinaire** : Contestation de sanction
- **Recours administratif** : Décision de gestion
- **Recours en notation** : Évaluation professionnelle
- **Recours en promotion** : Avancement de carrière

### 3. Traitement des Recours

#### 3.1 Circuit de Traitement
1. **Réception** : Enregistrement du dossier
2. **Instruction** : Analyse par la commission
3. **Enquête** : Investigation si nécessaire
4. **Délibération** : Décision collégiale
5. **Notification** : Communication de la décision

#### 3.2 Commission de Recours (DAC)
**Composition** :
- Représentants de l'administration
- Représentants du personnel
- Experts selon le domaine

**Rôle** :
- Examen des dossiers
- Auditions des parties
- Proposition de décision

### 4. Suivi des Recours

#### 4.1 États d'Avancement
- `SUBMITTED` : Recours déposé
- `UNDER_REVIEW` : En cours d'examen
- `SUSPENDED` : Suspendu (complément d'information)
- `ANALYSED` : Analysé par la commission
- `DECIDED` : Décision rendue
- `CLOSED` : Dossier clos

#### 4.2 Notifications
- Accusé de réception
- Demandes de compléments
- Convocations aux auditions
- Notification de décision

## Gestion des Utilisateurs

### 1. Comptes Utilisateurs
**Accès** : Administration → Utilisateurs → Comptes

**Types d'Utilisateurs** :
- **Employé** : Accès aux fonctions de base
- **Manager** : Validation des demandes de son équipe
- **RH** : Accès complet au module OptiHR
- **Administrateur** : Accès complet au système

### 2. Rôles et Permissions

#### Rôles Prédéfinis :
- **Super Admin** : Accès total
- **HR Manager** : Gestion RH complète
- **Department Head** : Gestion d'équipe
- **Employee** : Fonctions de base
- **Appeals Officer** : Gestion des recours

#### Permissions par Module :
- Création, lecture, modification, suppression
- Validation des demandes
- Accès aux rapports
- Configuration système

### 3. Journaux d'Activité
**Accès** : Administration → Journaux

**Informations Trackées** :
- Connexions/déconnexions
- Modifications de données
- Validations/rejets
- Actions sensibles

## Rapports et Statistiques

### 1. Rapports RH

#### 1.1 Rapports d'Effectifs
- Évolution des effectifs par période
- Répartition par département/poste
- Pyramide des âges
- Ancienneté moyenne

#### 1.2 Rapports d'Absences
- Taux d'absentéisme par service
- Répartition par type d'absence
- Soldes de congés
- Tendances saisonnières

#### 1.3 Rapports de Documents
- Volume de demandes par période
- Délais de traitement
- Types de documents les plus demandés

### 2. Rapports Recours
- Nombre de recours par période
- Taux de recours favorables
- Délais de traitement
- Analyse par type de recours

### 3. Export et Impression
**Formats Disponibles** :
- PDF pour impression
- Excel pour analyse
- CSV pour import

## FAQ

### Questions Générales

**Q : Comment récupérer mon mot de passe ?**
R : Utilisez la fonction "Mot de passe oublié" sur la page de connexion ou contactez l'administrateur.

**Q : Puis-je modifier mes informations personnelles ?**
R : Certaines informations peuvent être modifiées via votre profil. Pour les autres, contactez le service RH.

**Q : Comment recevoir les notifications ?**
R : Les notifications sont envoyées par email. Vérifiez vos paramètres de messagerie.

### Questions sur les Absences

**Q : Quel est le délai pour demander un congé ?**
R : Généralement 15 jours avant la date souhaitée, sauf urgence justifiée.

**Q : Puis-je annuler une demande en cours ?**
R : Oui, tant qu'elle n'est pas encore validée par votre hiérarchie.

**Q : Comment connaître mon solde de congés ?**
R : Consultez votre tableau de bord ou la section "Mes absences".

### Questions sur les Documents

**Q : Combien de temps pour recevoir un document ?**
R : Généralement 3-5 jours ouvrables selon le type de document.

**Q : Puis-je télécharger mes documents directement ?**
R : Oui, une fois traités, les documents sont disponibles en téléchargement.

### Questions Techniques

**Q : Le système est lent, que faire ?**
R : Videz votre cache navigateur ou contactez le support technique.

**Q : J'ai une erreur lors de l'upload de fichier**
R : Vérifiez la taille (max 10MB) et le format (PDF, JPG, PNG) du fichier.

## Support et Contact

### Support Technique
- **Email** : support-technique@optirh.com
- **Téléphone** : +XXX XX XX XX XX
- **Heures d'ouverture** : Lundi-Vendredi 8h-17h

### Service RH
- **Email** : rh@optirh.com
- **Bureau** : [Adresse du service RH]

### Formation
Des sessions de formation sont régulièrement organisées pour les nouveaux utilisateurs et les mises à jour du système.

---

*Ce manuel est mis à jour régulièrement. Version actuelle : 1.0*