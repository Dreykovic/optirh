@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'index'; @endphp

@section('help-content')
<div class="help-index">
    <div class="help-header">
        <h1>Guide Utilisateur OptiHR</h1>
        <p>Bienvenue dans le centre d'aide OptiHR. Explorez les différentes sections pour apprendre à utiliser toutes les fonctionnalités de la plateforme.</p>
    </div>

    <div class="help-grid">
        {{-- Introduction --}}
        <a href="{{ route('help.introduction') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-info-circle"></i>
            </div>
            <div class="help-card-content">
                <h3>1. Introduction</h3>
                <p>Présentation d'OptiHR, fonctionnalités principales et rôles utilisateurs.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Prise en main --}}
        <a href="{{ route('help.prise-en-main') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-hand"></i>
            </div>
            <div class="help-card-content">
                <h3>2. Prise en main</h3>
                <p>Connexion, navigation dans l'interface et configuration du mode sombre.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Tableau de bord --}}
        <a href="{{ route('help.tableau-de-bord') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-dashboard"></i>
            </div>
            <div class="help-card-content">
                <h3>3. Tableau de bord</h3>
                <p>KPIs, statistiques, calendrier des absences et graphiques de répartition.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Absences --}}
        <a href="{{ route('help.absences') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-calendar"></i>
            </div>
            <div class="help-card-content">
                <h3>4. Gestion des absences</h3>
                <p>Soumettre une demande, types d'absences et workflow de validation.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Documents --}}
        <a href="{{ route('help.documents') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-file-document"></i>
            </div>
            <div class="help-card-content">
                <h3>5. Gestion des documents</h3>
                <p>Demander une attestation, types de documents et suivi des demandes.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Personnel --}}
        <a href="{{ route('help.personnel') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-users-alt-5"></i>
            </div>
            <div class="help-card-content">
                <h3>6. Administration du personnel</h3>
                <p>Gestion des identifiants, directions, membres et contrats.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Espace Collaboratif --}}
        <a href="{{ route('help.espace-collaboratif') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-ui-messaging"></i>
            </div>
            <div class="help-card-content">
                <h3>7. Espace Collaboratif</h3>
                <p>Publications internes, partage d'informations et fichiers attachés.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Mon Espace --}}
        <a href="{{ route('help.mon-espace') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-user"></i>
            </div>
            <div class="help-card-content">
                <h3>8. Mon Espace</h3>
                <p>Informations personnelles, bulletins de paie et menu utilisateur.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- FAQ --}}
        <a href="{{ route('help.faq') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-question-circle"></i>
            </div>
            <div class="help-card-content">
                <h3>9. FAQ</h3>
                <p>Réponses aux questions les plus fréquemment posées.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>

        {{-- Problèmes --}}
        <a href="{{ route('help.problemes') }}" class="help-card">
            <div class="help-card-icon">
                <i class="icofont-tools"></i>
            </div>
            <div class="help-card-content">
                <h3>10. Résolution de problèmes</h3>
                <p>Solutions aux problèmes courants, astuces et raccourcis clavier.</p>
            </div>
            <div class="help-card-arrow">
                <i class="icofont-arrow-right"></i>
            </div>
        </a>
    </div>

    <div class="help-quick-start">
        <h2>Démarrage rapide</h2>
        <div class="quick-start-steps">
            <div class="quick-step">
                <span class="step-number">1</span>
                <span class="step-text">Connectez-vous avec vos identifiants</span>
            </div>
            <div class="quick-step">
                <span class="step-number">2</span>
                <span class="step-text">Explorez le tableau de bord</span>
            </div>
            <div class="quick-step">
                <span class="step-number">3</span>
                <span class="step-text">Soumettez votre première demande de congé</span>
            </div>
        </div>
    </div>
</div>
@endsection
