@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/css/help-page.css') }}">
   
@endsection

@section('admin-content')
<div class="help-container">
    <!-- En-tête principal -->
    <div class="help-header">
        <h1><i class="fas fa-question-circle"></i> Centre d'Aide OptiRH</h1>
        <p>Guides et ressources pour optimiser votre utilisation d'OptiRH</p>
    </div>

    <!-- Guides principaux -->
    <div class="guide-grid">
        <!-- Guide Tableau de bord -->
        <div class="guide-card">
            <div class="guide-header">
                <h3><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h3>
                <div class="guide-meta">
                    <span class="badge">49 étapes</span>
                    <span><i class="fas fa-clock"></i> 2 minutes</span>
                </div>
            </div>
            <div class="guide-content">
                <p class="guide-description">
                    Découvrez toutes les fonctionnalités du tableau de bord OptiRH et apprenez à naviguer efficacement dans l'application.
                </p>
                <div class="steps-preview">
                    <h5><i class="fas fa-list-ol"></i> Aperçu des étapes :</h5>
                    <div class="step-item">
                        <span class="step-number">1</span>
                        <span>Connexion et authentification</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">2</span>
                        <span>Navigation dans l'espace collaboratif</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">3</span>
                        <span>Gestion des absences et documents</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">4</span>
                        <span>Administration des utilisateurs</span>
                    </div>
                </div>
                <a href="https://scribehow.com/shared/Presentation_du_Tableau_de_bord_de_Optirh__Tm6_RxF0Q9S03ihgrbJQvg" 
                   target="_blank" 
                   class="guide-button">
                    <i class="fas fa-external-link-alt"></i>
                    Voir le guide complet
                </a>
            </div>
        </div>

        <!-- Guide Création d'identifiants -->
        <div class="guide-card">
            <div class="guide-header" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <h3><i class="fas fa-user-plus"></i> Création d'Identifiants</h3>
                <div class="guide-meta">
                    <span class="badge">9 étapes</span>
                    <span><i class="fas fa-clock"></i> 60 secondes</span>
                </div>
            </div>
            <div class="guide-content">
                <p class="guide-description">
                    Apprenez à créer des identifiants utilisateurs en tant que GRH et gérer les accès aux différents modules.
                </p>
                <div class="steps-preview">
                    <h5><i class="fas fa-list-ol"></i> Aperçu des étapes :</h5>
                    <div class="step-item">
                        <span class="step-number">1</span>
                        <span>Connexion en tant que GRH</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">2</span>
                        <span>Accès au module Utilisateurs</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">3</span>
                        <span>Sélection de l'employé</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">4</span>
                        <span>Attribution des rôles et permissions</span>
                    </div>
                </div>
                <a href="https://scribehow.com/shared/Creation_des_identifiant_utilisateurs__VNQrPH7aTCKDlvO1KnYM7A" 
                   target="_blank" 
                   class="guide-button">
                    <i class="fas fa-external-link-alt"></i>
                    Voir le guide complet
                </a>
            </div>
        </div>

        <!-- Guide Demande de congé -->
        <div class="guide-card">
            <div class="guide-header" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <h3><i class="fas fa-calendar-alt"></i> Demande de Congé</h3>
                <div class="guide-meta">
                    <span class="badge">13 étapes</span>
                    <span><i class="fas fa-clock"></i> 2 minutes</span>
                </div>
            </div>
            <div class="guide-content">
                <p class="guide-description">
                    Guide complet pour soumettre une demande de congé, depuis la connexion jusqu'à la validation de votre demande.
                </p>
                <div class="steps-preview">
                    <h5><i class="fas fa-list-ol"></i> Aperçu des étapes :</h5>
                    <div class="step-item">
                        <span class="step-number">1</span>
                        <span>Accès au module Absences</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">2</span>
                        <span>Sélection du type d'absence</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">3</span>
                        <span>Saisie des dates et adresse</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">4</span>
                        <span>Soumission et suivi</span>
                    </div>
                </div>
                <a href="https://scribehow.com/shared/Commment_faire_une_demande_de_conge__I7aHidNUTZOWieXaCUGXDw" 
                   target="_blank" 
                   class="guide-button">
                    <i class="fas fa-external-link-alt"></i>
                    Voir le guide complet
                </a>
            </div>
        </div>

        <!-- Carte pour les guides futurs -->
        <div class="guide-card" style="opacity: 0.7; position: relative;">
            <div class="guide-header" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);">
                <h3><i class="fas fa-plus-circle"></i> Autres Guides</h3>
                <div class="guide-meta">
                    <span class="badge">Bientôt</span>
                    <span><i class="fas fa-hourglass-half"></i> À venir</span>
                </div>
            </div>
            <div class="guide-content">
                <p class="guide-description">
                    D'autres guides détaillés seront ajoutés prochainement pour couvrir l'ensemble des fonctionnalités d'OptiRH.
                </p>
                <div class="steps-preview">
                    <h5><i class="fas fa-list-ol"></i> Guides prévus :</h5>
                    <div class="step-item">
                        <span class="step-number">•</span>
                        <span>Gestion des bulletins de paie</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">•</span>
                        <span>Administration des contrats</span>
                    </div>
                    <div class="step-item">
                        <span class="step-number">•</span>
                        <span>Rapports et statistiques</span>
                    </div>
                </div>
                <button class="guide-button" disabled style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-hourglass-half"></i>
                    En préparation
                </button>
            </div>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="quick-links">
        <h2><i class="fas fa-bolt"></i> Accès Rapide</h2>
        <div class="links-grid">
            <a href="/" class="quick-link" onclick="alert('Redirection vers le tableau de bord')">
                <h4><i class="fas fa-home"></i> Tableau de Bord</h4>
                <p>Retour à l'accueil principal</p>
            </a>
            <a href="{{ route('absences.requests') }}" class="quick-link" onclick="alert('Redirection vers les absences')">
                <h4><i class="fas fa-calendar-times"></i> Mes Absences</h4>
                <p>Gérer vos demandes de congé</p>
            </a>
            <a href="{{ route('documents.requests') }}" class="quick-link" onclick="alert('Redirection vers les documents')">
                <h4><i class="fas fa-file-alt"></i> Documents</h4>
                <p>Accéder à vos documents RH</p>
            </a>
            <a href="{{ route('employee.data') }}" class="quick-link" onclick="alert('Redirection vers le profil')">
                <h4><i class="fas fa-user-circle"></i> Mon Profil</h4>
                <p>Consulter vos informations</p>
            </a>

        </div>
    </div>

    <!-- Section support -->
    {{-- <div class="support-section">
        <h2><i class="fas fa-life-ring"></i> Besoin d'Aide ?</h2>
        <p>Notre équipe support est là pour vous accompagner dans l'utilisation d'OptiRH</p>
        <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <a href="mailto:support@optirh.com" class="support-button" aria-disabled="true">
                <i class="fas fa-envelope"></i>
                Contacter le Support
            </a>
            <a href="tel:+33123456789" class="support-button success">
                <i class="fas fa-phone"></i>
                Appel d'Urgence
            </a>
        </div>
    </div> --}}
</div>
@endsection

@push('plugins-js')
    <script>
        // Animation d'entrée pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.guide-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Animation pour les liens rapides
            const quickLinks = document.querySelectorAll('.quick-link');
            quickLinks.forEach((link, index) => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Effet de parallaxe léger sur l'en-tête
            window.addEventListener('scroll', function() {
                const header = document.querySelector('.help-header');
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.3;
                
                if (header) {
                    header.style.transform = `translateY(${rate}px)`;
                }
            });
        });

        // Fonction pour tracker les clics sur les guides
        function trackGuideClick(guideName) {
            console.log(`Guide consulté: ${guideName}`);
            // Ici vous pouvez ajouter votre logique d'analytics
        }

        // Ajout des event listeners pour le tracking
        document.querySelectorAll('.guide-button').forEach(button => {
            button.addEventListener('click', function() {
                const guideName = this.closest('.guide-card').querySelector('h3').textContent;
                trackGuideClick(guideName);
            });
        });
    </script>
@endpush

@push('js')
    <script>
        // Script spécifique à la page d'aide
        console.log('Page d\'aide OptiRH chargée avec succès');
        
        // Fonction pour améliorer l'accessibilité
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });
    </script>
@endpush