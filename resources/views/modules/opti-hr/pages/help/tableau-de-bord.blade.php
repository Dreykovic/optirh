@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'tableau-de-bord'; @endphp

@section('help-content')
<div class="help-section">
    <h1>3. Tableau de bord</h1>

    <h2>Vue d'ensemble</h2>
    <p>Le tableau de bord est votre page d'accueil. Il présente une synthèse de l'activité RH de votre organisation.</p>

    <h2>Actions rapides</h2>
    <p>Quatre boutons d'accès direct aux actions les plus fréquentes :</p>
    <table class="guide-table">
        <tr>
            <th>Bouton</th>
            <th>Action</th>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-success">Ajouter un employé</span></td>
            <td>Accéder à la liste des employés pour en ajouter</td>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-info">Ajouter un département</span></td>
            <td>Créer une nouvelle direction/département</td>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-warning">Enregistrer une absence</span></td>
            <td>Soumettre une nouvelle demande de congé</td>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-info">Nouvelle publication</span></td>
            <td>Publier une information dans l'espace collaboratif</td>
        </tr>
    </table>

    <h2>Indicateurs clés (KPIs)</h2>
    <p>Les KPIs (Key Performance Indicators) sont des mesures quantifiables qui permettent d'évaluer la performance de l'organisation.</p>
    <table class="guide-table">
        <tr>
            <th>Indicateur</th>
            <th>Description</th>
        </tr>
        <tr>
            <td>Taux d'absentéisme</td>
            <td>Pourcentage d'absences sur la période</td>
        </tr>
        <tr>
            <td>Temps moyen traitement</td>
            <td>Durée moyenne de traitement des demandes</td>
        </tr>
        <tr>
            <td>Taux d'approbation</td>
            <td>Pourcentage de demandes approuvées</td>
        </tr>
    </table>

    <h2>Statistiques rapides</h2>
    <p>Cartes affichant les données essentielles avec évolution par rapport au mois précédent :</p>
    <ul>
        <li><strong>Total employés</strong> - Nombre d'employés actifs</li>
        <li><strong>Départements</strong> - Nombre de directions/services</li>
        <li><strong>Absences en attente</strong> - Demandes à traiter</li>
        <li><strong>Documents en attente</strong> - Demandes de documents à traiter</li>
    </ul>

    <h2>Calendrier des absences</h2>
    <p>Visualisation mensuelle des absences planifiées. Fonctionnalités disponibles :</p>
    <ul>
        <li><strong>Navigation</strong> : Boutons pour changer de mois</li>
        <li><strong>Aujourd'hui</strong> : Revenir à la date du jour</li>
        <li><strong>Vue Mois/Liste</strong> : Basculer entre l'affichage calendrier et liste</li>
        <li><strong>Filtre "Toutes les absences"</strong> : Afficher/masquer toutes les absences</li>
    </ul>

    <h2>Graphiques de répartition</h2>
    <ul>
        <li><strong>Répartition par département</strong> : Diagramme des effectifs par direction</li>
        <li><strong>Répartition par genre</strong> : Diagramme Homme/Femme</li>
    </ul>

    <h2>Tableaux récapitulatifs</h2>
    <p><strong>Demandes d'absence récentes</strong> : Liste des dernières demandes avec employé, type, période, jours, statut et lien vers les détails.</p>
    <p><strong>Demandes de documents</strong> : Liste des dernières demandes de documents avec type, date et statut.</p>
    <p><strong>Publications récentes</strong> : Dernières publications de l'espace collaboratif.</p>

    <h2>Bouton Actualiser</h2>
    <p>Cliquez sur le bouton <strong>Actualiser</strong> en haut à droite pour rafraîchir les données du tableau de bord.</p>
</div>
@endsection
