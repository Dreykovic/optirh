@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'documents'; @endphp

@section('help-content')
<div class="help-section">
    <h1>5. Gestion des documents</h1>

    <h2>5.1 Demander un document</h2>
    <div class="guide-step">
        <span class="guide-step-number">1</span>
        Accédez à <strong>Documents - Nouvelle demande</strong>
    </div>
    <div class="guide-step">
        <span class="guide-step-number">2</span>
        Sélectionnez le <strong>Type de document</strong> souhaité dans la liste
    </div>
    <div class="guide-step">
        <span class="guide-step-number">3</span>
        Définissez la <strong>Période du document</strong> (date de début et fin si applicable)
    </div>
    <div class="guide-step">
        <span class="guide-step-number">4</span>
        Cliquez sur <strong>Soumettre la demande</strong>
    </div>

    <h2>5.2 Types de documents disponibles</h2>
    <table class="guide-table">
        <tr>
            <th>Document</th>
            <th>Description</th>
        </tr>
        <tr>
            <td>Attestation de Stage</td>
            <td>Attestation remise en fin de stage</td>
        </tr>
        <tr>
            <td>Attestation de travail</td>
            <td>Certificat attestant de votre emploi</td>
        </tr>
    </table>

    <h2>5.3 Suivi des demandes</h2>
    <p>Accédez à <strong>Documents - Mes documents</strong> pour consulter l'état de vos demandes.</p>

    <h3>Filtres disponibles</h3>
    <ul>
        <li><strong>Toutes</strong> : Toutes les demandes</li>
        <li><strong>À Traiter</strong> : Demandes en attente de traitement</li>
        <li><strong>Terminées</strong> : Documents prêts</li>
    </ul>

    <div class="guide-tip">
        Contactez le service RH pour toute question sur les documents disponibles.
    </div>
</div>
@endsection
