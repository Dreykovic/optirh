# Changelog

## [Unreleased]

### 🚀 Ajouté
- Ajout du module **Recours** avec vues, contrôleurs, routes et migrations initiales.  
- Ajout du **contrôleur AnnualDecision** et gestion des décisions annuelles.  
- Ajout des fonctionnalités de **journalisation** avec `LogsActivity` et `ActivityLogController`.  
- Ajout de l’**authentification améliorée** et gestion des sessions.  
- Ajout de permissions et rôles pour OptiHR et Recours.  
- Ajout du **Dockerfile** et configuration `docker-compose`.  
- Ajout d’attributs `is_deductible` pour la gestion des absences et soldes.  
- Ajout des **notifications par e-mail** pour les demandes de documents et absences.  
- Ajout d’un système de **balance de congés** et affichage dans l’interface.  
- Ajout de la gestion des **fichiers PDF** (absences, décisions, recours).  
- Ajout d’un **dashboard RH** avec gestion des publications et filtres dynamiques.  

### 🛠️ Modifié / Amélioré
- Refactorisation massive des contrôleurs (`AbsenceController`, `PublicationController`, `UserController`, `AuthController`, etc.) pour améliorer lisibilité, validation et gestion des erreurs.  
- Amélioration du **formulaire de demandes d’absence** : ajout d’indicateurs de statut, contrôle du caractère déductible et meilleure ergonomie.  
- Refactorisation des templates e-mail (dates, accessibilité, performance).  
- Amélioration de la structure des vues (`modals`, `sidebar`, `headers`).  
- Réorganisation des namespaces pour OptiHR et Recours.  
- Simplification des middlewares et des routes.  
- Amélioration de la logique de notifications et du suivi des actions utilisateurs.  
- Amélioration de la documentation de l’architecture.  

### 🐛 Corrigé
- Correction des **seeders** d’absence et de rôles (commas manquants, permissions inutilisées).  
- Correction de la gestion des absences : calculs de soldes, labels, statuts, annulation.  
- Correction des couleurs d’alerte (`success`, `warning`) pour plus de cohérence.  
- Correction des problèmes d’**authentification** et de permissions sur le dashboard.  
- Correction des erreurs liées aux migrations (`absence_balance`, `duties`).  
- Correction de l’upload et preview de fichiers dans les publications.  

### 📖 Documentation
- Ajout d’une **documentation complète** du projet et des commentaires de code.  
- Ajout de **métadonnées GitHub** (templates d’issues/PR).  
- Documentation de l’architecture avec vue d’ensemble des modules.  

---

## [v1.0.0] – 2025-08-28
Première version candidate basée sur la branche `develop`.  
Inclut les modules de gestion des absences, des décisions, des publications, des recours, avec authentification, notifications, et journalisation des actions.  
