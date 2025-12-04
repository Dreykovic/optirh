@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'personnel'; @endphp

@section('help-content')
<div class="help-section">
    <h1>6. Administration du personnel</h1>
    <div class="guide-info">Cette section est réservée aux utilisateurs ayant des droits d'administration (GRH, Directeurs).</div>

    <h2>6.1 Gestion des identifiants</h2>
    <p>Accès : <strong>Administration - Identifiants</strong></p>

    <h3>Fonctionnalités</h3>
    <ul>
        <li><strong>Ajouter</strong> : Créer un nouveau compte utilisateur</li>
        <li><strong>Filtrer</strong> : Toutes, Activés, Non Activés</li>
        <li><strong>Rechercher</strong> : Trouver un utilisateur par nom ou email</li>
    </ul>

    <h3>Informations affichées</h3>
    <ul>
        <li>Membre (nom complet)</li>
        <li>Username (identifiant de connexion)</li>
        <li>Email</li>
        <li>Date d'intégration</li>
        <li>Rôle (DG, DSAF, GRH, etc.)</li>
        <li>Status (Activé/Désactivé)</li>
    </ul>

    <h2>6.2 Gestion des directions</h2>
    <p>Accès : <strong>Personnel - Directions</strong></p>

    <h3>Actions possibles</h3>
    <ul>
        <li><strong>Ajouter</strong> : Créer une nouvelle direction/département</li>
        <li><strong>Actions</strong> : Modifier ou supprimer une direction existante</li>
    </ul>

    <h3>Informations d'une direction</h3>
    <ul>
        <li>Directeur assigné</li>
        <li>Code de la direction (DG, DSAF, etc.)</li>
        <li>Définition/Description</li>
    </ul>

    <h2>6.3 Gestion des membres (Employés)</h2>
    <p>Accès : <strong>Personnel - Membres</strong></p>

    <h3>Liste des employés</h3>
    <p>Affiche tous les employés avec : Nom, Contact, Email, Adresse.</p>

    <h3>Actions disponibles</h3>
    <ul>
        <li><strong>Ajouter</strong> : Enregistrer un nouvel employé</li>
        <li><strong>Directions</strong> : Filtrer par direction</li>
        <li><strong>Menu Actions</strong> : Modifier, voir les détails, supprimer</li>
    </ul>

    <h2>6.4 Gestion des contrats</h2>
    <p>Accès : <strong>Personnel - Contrats</strong></p>

    <h3>Filtres par statut</h3>
    <ul>
        <li><strong>En Cours</strong> : Contrats actifs</li>
        <li><strong>Suspendus</strong> : Contrats temporairement suspendus</li>
        <li><strong>Terminés</strong> : Contrats arrivés à échéance</li>
        <li><strong>Démissionnés</strong> : Employés ayant démissionné</li>
        <li><strong>Licenciés</strong> : Contrats résiliés</li>
        <li><strong>Supprimés</strong> : Contrats archivés</li>
    </ul>

    <h3>Informations d'un contrat</h3>
    <ul>
        <li>Employé</li>
        <li>Direction</li>
        <li>Poste</li>
        <li>Date d'embauche</li>
        <li>Type (Full-Time, Part-Time, etc.)</li>
        <li>Solde de congés</li>
    </ul>

    <h2>6.5 Bulletins de paie (Administration)</h2>
    <p>Accès : <strong>Personnel - Bulletins de paie</strong></p>

    <p>Cette page permet d'importer les bulletins de paie depuis Sage Paie :</p>
    <div class="guide-step">
        <span class="guide-step-number">1</span>
        Cliquez sur <strong>Choose File</strong> pour sélectionner le fichier
    </div>
    <div class="guide-step">
        <span class="guide-step-number">2</span>
        Vérifiez le fichier sélectionné
    </div>
    <div class="guide-step">
        <span class="guide-step-number">3</span>
        Cliquez sur <strong>ENVOYER</strong> pour distribuer les bulletins
    </div>

    <div class="guide-warning">
        Le fichier doit être au format compatible Sage Paie pour être traité correctement.
    </div>
</div>
@endsection
