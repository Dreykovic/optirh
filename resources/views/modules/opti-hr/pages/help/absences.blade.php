@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'absences'; @endphp

@section('help-content')
<div class="help-section">
    <h1>4. Gestion des absences</h1>

    <h2>4.1 Soumettre une demande d'absence</h2>

    <div class="guide-step">
        <span class="guide-step-number">1</span>
        Accédez à <strong>Absences - Soumettre une demande</strong> ou cliquez sur <strong>"Demander Un Congés"</strong> dans la barre supérieure
    </div>
    <div class="guide-step">
        <span class="guide-step-number">2</span>
        <strong>Type d'absence</strong> : Sélectionnez le motif dans la liste (annuel, maternité, exceptionnel)
    </div>
    <div class="guide-step">
        <span class="guide-step-number">3</span>
        <strong>Période d'absence</strong> : Choisissez la date de début et la date de fin
    </div>
    <div class="guide-step">
        <span class="guide-step-number">4</span>
        <strong>Adresse pendant l'absence</strong> : Indiquez où vous serez joignable
    </div>
    <div class="guide-step">
        <span class="guide-step-number">5</span>
        <strong>Motif</strong> (optionnel) : Expliquez brièvement la raison (max 1000 caractères)
    </div>
    <div class="guide-step">
        <span class="guide-step-number">6</span>
        <strong>Justificatif</strong> (optionnel) : Joignez un document (PDF, JPG, PNG - max 5 Mo)
    </div>
    <div class="guide-step">
        <span class="guide-step-number">7</span>
        Vérifiez le <strong>Résumé de votre demande</strong> puis cliquez sur <strong>Soumettre la demande</strong>
    </div>

    <div class="guide-info">
        Votre solde de congés disponible est affiché en haut de la page. Le nombre de jours ouvrés est calculé automatiquement selon les dates sélectionnées.
    </div>

    <h2>4.2 Types d'absences</h2>
    <table class="guide-table">
        <tr>
            <th>Type</th>
            <th>Description</th>
            <th>Déductible du solde</th>
        </tr>
        <tr>
            <td>Annuel</td>
            <td>Congés payés annuels</td>
            <td><span class="guide-badge guide-badge-success">Oui</span></td>
        </tr>
        <tr>
            <td>Maternité</td>
            <td>Congé accordé aux salariées enceintes</td>
            <td><span class="guide-badge guide-badge-danger">Non</span></td>
        </tr>
        <tr>
            <td>Exceptionnel</td>
            <td>Absence pour raison spécifique</td>
            <td><span class="guide-badge guide-badge-danger">Non</span></td>
        </tr>
    </table>

    <h2>4.3 Liste des demandes</h2>
    <p>Accédez à <strong>Absences - Liste des demandes</strong> pour voir toutes vos demandes et celles à traiter.</p>

    <h3>Filtres disponibles</h3>
    <ul>
        <li><strong>Toutes</strong> : Toutes les demandes</li>
        <li><strong>Mes demandes</strong> : Vos propres demandes uniquement</li>
        <li><strong>À valider</strong> : Demandes en attente de votre validation</li>
    </ul>

    <h3>Onglets de statut</h3>
    <ul>
        <li><strong>À traiter</strong> : Demandes actives en cours de traitement</li>
        <li><strong>Historique</strong> : Demandes terminées (approuvées/rejetées)</li>
        <li><strong>Annulées</strong> : Demandes annulées</li>
    </ul>

    <h2>4.4 Workflow de validation</h2>
    <p>Chaque demande d'absence suit un processus de validation en plusieurs étapes :</p>
    <div class="guide-workflow">
        <span class="guide-workflow-step">Demande</span>
        <span class="guide-workflow-arrow"><i class="icofont-arrow-right"></i></span>
        <span class="guide-workflow-step">Chef Direct</span>
        <span class="guide-workflow-arrow"><i class="icofont-arrow-right"></i></span>
        <span class="guide-workflow-step">GRH</span>
        <span class="guide-workflow-arrow"><i class="icofont-arrow-right"></i></span>
        <span class="guide-workflow-step">Direction</span>
    </div>

    <h3>Statuts possibles</h3>
    <table class="guide-table">
        <tr>
            <th>Statut</th>
            <th>Signification</th>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-warning">En attente</span></td>
            <td>En attente de validation à une étape</td>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-info">IN_PROGRESS</span></td>
            <td>En cours de traitement</td>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-success">Approuvée</span></td>
            <td>Demande validée par tous les niveaux</td>
        </tr>
        <tr>
            <td><span class="guide-badge guide-badge-danger">Annulée</span></td>
            <td>Demande annulée par l'employé</td>
        </tr>
    </table>

    <h2>4.5 Annuler une demande</h2>
    <p>Vous pouvez annuler une demande tant qu'elle n'est pas complètement validée en cliquant sur le bouton <strong>Annuler</strong> rouge dans la liste des demandes.</p>

    <h2>4.6 Décisions annuelles</h2>
    <p>La section <strong>Absences - Décisions annuelles</strong> permet aux administrateurs de gérer les décisions officielles relatives aux congés annuels avec numéro de référence, année et date d'application.</p>
</div>
@endsection
