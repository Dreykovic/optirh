@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'espace-collaboratif'; @endphp

@section('help-content')
<div class="help-section">
    <h1>7. Espace Collaboratif</h1>

    <p>L'Espace Collaboratif est un outil de communication interne permettant de partager des notes et informations avec l'ensemble du personnel.</p>

    <h2>7.1 Consulter les publications</h2>
    <p>Accédez à <strong>Espace Collaboratif</strong> dans le menu principal.</p>

    <h3>Filtres disponibles</h3>
    <ul>
        <li><strong>Toutes</strong> : Toutes les publications</li>
        <li><strong>Publiées</strong> : Publications visibles par tous</li>
        <li><strong>En attente</strong> : Publications en attente de validation</li>
        <li><strong>Archivées</strong> : Publications archivées</li>
    </ul>

    <h2>7.2 Créer une publication</h2>
    <div class="guide-step">
        <span class="guide-step-number">1</span>
        Dans le champ en bas de page, saisissez le <strong>Titre de votre publication</strong> (max 255 caractères)
    </div>
    <div class="guide-step">
        <span class="guide-step-number">2</span>
        Ajoutez le contenu de votre message
    </div>
    <div class="guide-step">
        <span class="guide-step-number">3</span>
        Optionnellement, joignez des fichiers
    </div>
    <div class="guide-step">
        <span class="guide-step-number">4</span>
        Publiez votre message
    </div>

    <h2>7.3 Gérer les publications</h2>
    <p>Chaque publication dispose d'un menu <strong>Actions</strong> permettant de :</p>
    <ul>
        <li>Modifier le contenu</li>
        <li>Archiver la publication</li>
        <li>Supprimer la publication</li>
    </ul>

    <h2>7.4 Fichiers attachés</h2>
    <p>Les publications peuvent contenir des fichiers joints (PDF, images, etc.) téléchargeables en cliquant sur l'icône de téléchargement à côté du nom du fichier.</p>
</div>
@endsection
