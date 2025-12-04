@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'introduction'; @endphp

@section('help-content')
<div class="help-section">
    <h1>1. Introduction</h1>

    <h2>Qu'est-ce qu'OptiHR ?</h2>
    <p>OptiHR est une application de gestion des ressources humaines intégrée à la plateforme ARCOP MAN. Elle permet de centraliser et simplifier la gestion du personnel, des absences, des documents administratifs et de la communication interne.</p>

    <h2>Fonctionnalités principales</h2>
    <table class="guide-table">
        <tr>
            <th>Module</th>
            <th>Description</th>
        </tr>
        <tr>
            <td><strong>Gestion des absences</strong></td>
            <td>Demandes de congés, validation hiérarchique, suivi du solde</td>
        </tr>
        <tr>
            <td><strong>Documents RH</strong></td>
            <td>Demandes d'attestations, certificats de travail, documents officiels</td>
        </tr>
        <tr>
            <td><strong>Administration</strong></td>
            <td>Gestion des employés, directions, contrats et identifiants</td>
        </tr>
        <tr>
            <td><strong>Espace Collaboratif</strong></td>
            <td>Publications internes, partage d'informations</td>
        </tr>
        <tr>
            <td><strong>Bulletins de paie</strong></td>
            <td>Consultation et téléchargement des fiches de paie</td>
        </tr>
    </table>

    <h2>Rôles utilisateurs</h2>
    <table class="guide-table">
        <tr>
            <th>Rôle</th>
            <th>Code</th>
            <th>Permissions</th>
        </tr>
        <tr>
            <td>Directeur Général</td>
            <td><span class="guide-badge guide-badge-success">DG</span></td>
            <td>Validation finale, accès complet</td>
        </tr>
        <tr>
            <td>Directeur (DSAF, etc.)</td>
            <td><span class="guide-badge guide-badge-info">DSAF</span></td>
            <td>Validation hiérarchique, gestion d'équipe</td>
        </tr>
        <tr>
            <td>Gestionnaire RH</td>
            <td><span class="guide-badge guide-badge-warning">GRH</span></td>
            <td>Administration complète du personnel et des demandes</td>
        </tr>
        <tr>
            <td>Employé</td>
            <td><span class="guide-badge guide-badge-info">—</span></td>
            <td>Consultation, soumission de demandes</td>
        </tr>
    </table>
</div>
@endsection
