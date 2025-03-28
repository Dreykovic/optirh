@extends('base')
@section('content')
    @guest
        <div class="container-fluid p-0">
            <div class="row g-0 min-vh-100">
                <!-- Côté gauche (illustration et branding) -->
                <div class="col-lg-6 d-none d-lg-block position-relative overflow-hidden bg-gradient">
                    <!-- Fond avec motif plus subtil -->
                    <div class="position-absolute w-100 h-100 pattern-overlay"></div>

                    <!-- Contenu centré -->
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 p-5 position-relative">
                        <div class="text-center mb-5 brand-container">
                            <div class="logo-container mb-4">
                                <!-- Logo placeholder - remplacer par votre logo -->
                                <div class="app-logo">
                                    <span>A</span>
                                </div>
                            </div>
                            <h1 class="brand-title">ARCOP MAN</h1>
                            <p class="brand-tagline">Plateforme de gestion intégrée</p>
                        </div>

                        <!-- Illustration moderne -->
                        <div class="illustration-container">
                            <div class="shape shape-1"></div>
                            <div class="shape shape-2"></div>
                            <div class="shape shape-3"></div>
                            <div class="illustration">
                                <div class="illustration-element"></div>
                            </div>
                        </div>

                        <!-- Citation ou slogan -->
                        <div class="mt-auto text-center quote-container">
                            <blockquote class="quote">
                                "Simplicité, efficacité, performance."
                            </blockquote>
                        </div>
                    </div>
                </div>

                <!-- Côté droit (formulaire) -->
                <div class="col-lg-6 bg-white">
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 p-4">
                        <!-- Logo pour mobile -->
                        <div class="d-block d-lg-none text-center mb-4 w-100">
                            <div class="d-inline-block app-logo-sm">
                                <span>A</span>
                            </div>
                            <h2 class="mt-2 brand-title-sm">ARCOP MAN</h2>
                        </div>

                        <!-- Formulaire de connexion -->
                        <div class="login-card">
                            <div class="card-body p-4">
                                <div class="form-header">
                                    <h3 class="form-title">Bienvenue</h3>
                                    <p class="form-subtitle">Connectez-vous pour accéder à votre espace</p>
                                </div>

                                @yield('auth-content')


                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-4 login-footer">
                            <p class="copyright">&copy; {{ date('Y') }} ARCOP - Tous droits réservés</p>
                            <div class="footer-links">
                                <a href="#" class="footer-link">Politique de confidentialité</a>
                                <a href="#" class="footer-link">Conditions d'utilisation</a>
                                <a href="#" class="footer-link">Support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Styles CSS améliorés -->
        <style>
            /* Variables */
            :root {
                --primary: #3a7e3d;
                --primary-light: #4c9e50;
                --primary-dark: #2c6a2f;
                --secondary: #f8d948;
                --secondary-light: #ffe978;
                --light-bg: #f8f9fa;
                --dark-text: #343a40;
                --light-text: #6c757d;
                --border-radius: 10px;
                --card-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
                --transition: all 0.3s ease;
            }

            /* Styles généraux */
            body {
                font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
                color: var(--dark-text);
            }

            /* Côté gauche */
            .bg-gradient {
                background: linear-gradient(135deg, #3a7e3d 0%, #4c9e50 100%);
                color: white;
                position: relative;
            }

            .pattern-overlay {
                background-image: radial-gradient(rgba(255, 255, 255, 0.1) 2px, transparent 2px);
                background-size: 30px 30px;
                opacity: 0.4;
            }

            /* Logo et branding */
            .brand-container {
                margin-bottom: 2rem;
                animation: fadeDown 1s ease-out;
            }

            .app-logo {
                width: 80px;
                height: 80px;
                border-radius: 16px;
                background-color: white;
                color: var(--primary);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2.5rem;
                font-weight: 700;
                margin: 0 auto;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                position: relative;
                overflow: hidden;
                animation: pulse 4s infinite;
            }

            .app-logo:after {
                content: '';
                position: absolute;
                top: -10px;
                right: -10px;
                width: 40px;
                height: 40px;
                background: var(--secondary);
                border-radius: 50%;
                opacity: 0.5;
            }

            .app-logo-sm {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                background-color: var(--primary);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                font-weight: 700;
            }

            .brand-title {
                font-size: 2.2rem;
                font-weight: 700;
                letter-spacing: -0.5px;
                margin-top: 1rem;
                margin-bottom: 0.5rem;
                animation: fadeIn 1.2s ease-out;
            }

            .brand-title-sm {
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--primary);
            }

            .brand-tagline {
                font-size: 1.2rem;
                opacity: 0.9;
                animation: fadeIn 1.4s ease-out;
            }

            /* Illustration */
            .illustration-container {
                position: relative;
                width: 100%;
                max-width: 440px;
                height: 300px;
                animation: fadeUp 1s ease-out 0.5s both;
            }

            .illustration {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                overflow: hidden;
                backdrop-filter: blur(10px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                animation: float 6s ease-in-out infinite;
            }

            .illustration-element {
                width: 70%;
                height: 70%;
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                position: relative;
                overflow: hidden;
            }

            .illustration-element:before {
                content: '';
                position: absolute;
                width: 150%;
                height: 150%;
                background: linear-gradient(90deg, var(--secondary-light), transparent);
                animation: rotate 10s linear infinite;
                transform-origin: center;
            }

            .shape {
                position: absolute;
                border-radius: 50%;
                z-index: -1;
            }

            .shape-1 {
                width: 120px;
                height: 120px;
                background-color: rgba(255, 255, 255, 0.1);
                top: -30px;
                left: 10%;
                animation: float 7s ease-in-out infinite;
            }

            .shape-2 {
                width: 80px;
                height: 80px;
                background-color: rgba(255, 255, 255, 0.15);
                bottom: 10%;
                left: 20%;
                animation: float 5s ease-in-out infinite 1s;
            }

            .shape-3 {
                width: 60px;
                height: 60px;
                background-color: var(--secondary);
                opacity: 0.2;
                top: 40%;
                right: 10%;
                animation: float 9s ease-in-out infinite 2s;
            }

            /* Citation */
            .quote-container {
                margin-top: 2rem;
                animation: fadeUp 1.5s ease-out;
            }

            .quote {
                font-style: italic;
                font-size: 1.1rem;
                position: relative;
                opacity: 0.9;
            }

            /* Formulaire de connexion */
            .login-card {
                width: 100%;
                max-width: 400px;
                background: white;
                border-radius: var(--border-radius);
                box-shadow: var(--card-shadow);
                overflow: hidden;
                transition: var(--transition);
                animation: fadeIn 1s ease-out;
                border: none;
            }

            .form-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .form-title {
                color: var(--primary);
                font-weight: 600;
                margin-bottom: 0.5rem;
                font-size: 1.5rem;
            }

            .form-subtitle {
                color: var(--light-text);
                font-size: 0.95rem;
            }

            /* Form elements overrides */
            .form-control {
                border-radius: var(--border-radius);
                padding: 0.6rem 1rem;
                border: 1px solid #dee2e6;
                font-size: 1rem;
                transition: var(--transition);
            }

            .form-control:focus {
                box-shadow: 0 0 0 3px rgba(58, 126, 61, 0.15);
                border-color: var(--primary);
            }

            .form-floating label {
                padding: 0.6rem 1rem;
            }

            .btn-primary {
                background-color: var(--primary);
                border-color: var(--primary);
                border-radius: var(--border-radius);
                padding: 0.6rem 1.5rem;
                font-weight: 500;
                transition: var(--transition);
            }

            .btn-primary:hover,
            .btn-primary:focus {
                background-color: var(--primary-dark);
                border-color: var(--primary-dark);
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* Options alternatives de connexion */
            .alt-login-options {
                margin-top: 1.5rem;
            }

            .divider {
                text-align: center;
                position: relative;
                margin: 1.5rem 0;
                color: var(--light-text);
                font-size: 0.9rem;
            }

            .divider:before,
            .divider:after {
                content: '';
                position: absolute;
                top: 50%;
                width: calc(50% - 30px);
                height: 1px;
                background-color: #e9ecef;
            }

            .divider:before {
                left: 0;
            }

            .divider:after {
                right: 0;
            }

            .divider span {
                background-color: white;
                padding: 0 1rem;
                position: relative;
            }

            .btn-outline-secondary {
                border-radius: var(--border-radius);
                transition: var(--transition);
            }

            .btn-outline-secondary:hover {
                background-color: #f8f9fa;
                transform: translateY(-2px);
            }

            /* Footer */
            .login-footer {
                text-align: center;
                color: var(--light-text);
                font-size: 0.85rem;
                width: 100%;
                max-width: 400px;
            }

            .copyright {
                margin-bottom: 0.5rem;
            }

            .footer-links {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .footer-link {
                color: var(--light-text);
                text-decoration: none;
                transition: var(--transition);
            }

            .footer-link:hover {
                color: var(--primary);
            }

            /* Animations */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes fadeDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-15px);
                }
            }

            @keyframes pulse {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.05);
                }
            }

            @keyframes rotate {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            /* Media queries */
            @media (max-width: 991.98px) {
                .login-card {
                    max-width: 350px;
                }
            }
        </style>
    @endguest
@endsection
