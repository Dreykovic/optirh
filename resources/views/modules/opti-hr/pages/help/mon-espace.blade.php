@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'mon-espace'; @endphp

@section('help-content')
<div class="help-section">
    <h1>8. Mon Espace</h1>

    <h2>8.1 Mes informations</h2>
    <p>Accès : <strong>Mon Espace - Mes Données - Mes informations</strong> ou via le menu utilisateur <strong>Paramètres Profil</strong></p>

    <h3>Section Identité</h3>
    <ul>
        <li>Nom et Prénoms</li>
        <li>Nationalité</li>
        <li>Religion</li>
        <li>Situation matrimoniale</li>
        <li>Genre</li>
        <li>Date de naissance</li>
    </ul>

    <h3>Section Adresse</h3>
    <ul>
        <li>Email professionnel</li>
        <li>Contact téléphonique</li>
        <li>Contact d'urgence</li>
        <li>Adresse complète (rue, ville, quartier)</li>
    </ul>

    <h3>Section Compte Banque</h3>
    <ul>
        <li>Nom de la banque</li>
        <li>Numéro de compte</li>
        <li>IBAN et Swift</li>
        <li>Clé RIB, Code Banque, Code Guichet</li>
    </ul>

    <p>Cliquez sur <strong>ENREGISTRER</strong> pour sauvegarder vos modifications.</p>

    <h2>8.2 Mes bulletins de paie</h2>
    <p>Accès : <strong>Mon Espace - Mes Données - Mes bulletins</strong> ou via le menu utilisateur</p>

    <p>Cette page liste tous vos bulletins de paie disponibles. Utilisez la recherche et la pagination pour trouver un bulletin spécifique.</p>

    <h2>8.3 Menu utilisateur</h2>
    <p>Cliquez sur votre nom en haut à droite pour accéder au menu rapide :</p>
    <ul>
        <li><strong>Bulletins de paie</strong> : Accès direct à vos fiches de paie</li>
        <li><strong>Paramètres Profil</strong> : Modifier vos informations</li>
        <li><strong>Se Déconnecter</strong> : Fermer votre session</li>
    </ul>

    <div class="guide-tip">
        Pensez à vous déconnecter après chaque utilisation sur un ordinateur partagé.
    </div>
</div>
@endsection
