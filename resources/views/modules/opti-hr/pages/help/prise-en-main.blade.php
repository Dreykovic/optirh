@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'prise-en-main'; @endphp

@section('help-content')
<div class="help-section">
    <h1>2. Prise en main rapide</h1>

    <h2>Connexion à l'application</h2>
    <div class="guide-step">
        <span class="guide-step-number">1</span>
        Accédez à la page de connexion ARCOP MAN
    </div>
    <div class="guide-step">
        <span class="guide-step-number">2</span>
        Entrez votre <strong>Adresse Email</strong> professionnelle
    </div>
    <div class="guide-step">
        <span class="guide-step-number">3</span>
        Saisissez votre <strong>Mot de passe</strong>
    </div>
    <div class="guide-step">
        <span class="guide-step-number">4</span>
        Cochez <em>"Se souvenir de moi"</em> si vous êtes sur un appareil personnel
    </div>
    <div class="guide-step">
        <span class="guide-step-number">5</span>
        Cliquez sur <strong>Se connecter</strong>
    </div>

    <div class="guide-tip">
        En cas d'oubli de mot de passe, cliquez sur "Mot de passe oublié?" pour recevoir un lien de réinitialisation par email.
    </div>

    <h2>Navigation dans l'interface</h2>
    <p>L'interface est composée de plusieurs zones :</p>

    <h3>Barre latérale gauche (Menu principal)</h3>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-home"></i></span><strong>Tableau de bord</strong> - Vue d'ensemble et statistiques</div>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-ui-messaging"></i></span><strong>Espace Collaboratif</strong> - Publications et annonces</div>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-calendar"></i></span><strong>Absences</strong> - Demandes de congés</div>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-file-document"></i></span><strong>Documents</strong> - Attestations et certificats</div>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-key"></i></span><strong>Identifiants</strong> - Gestion des comptes (Admin)</div>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-users-alt-5"></i></span><strong>Personnel</strong> - Employés et contrats (Admin)</div>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-user"></i></span><strong>Mes Données</strong> - Informations personnelles</div>
    <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-question-circle"></i></span><strong>Aide</strong> - Centre d'assistance</div>

    <h3>Barre supérieure</h3>
    <p>Contient le bouton d'action rapide <strong>"Demander Un Congés"</strong>, l'affichage du solde de congés et le menu utilisateur avec votre profil.</p>

    <h2>Mode Sombre</h2>
    <p>Activez le mode sombre via le commutateur <strong>"Mode Sombre"</strong> dans la barre latérale pour un affichage plus confortable en conditions de faible luminosité.</p>

    <h2>Portail d'Applications</h2>
    <p>Le lien <strong>"Portail d'Applications"</strong> en bas de la barre latérale vous permet de retourner au portail principal ARCOP MAN pour accéder aux autres applications.</p>
</div>
@endsection
