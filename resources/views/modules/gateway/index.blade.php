<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARCOP - Portail d'Applications</title>
    <link rel="stylesheet" href="{{ asset('assets/css/my-task.style.min.css') }}">
</head>

<body>
    <div class="app-wrapper">
        <!-- Inclure le header -->
        @include('modules.gateway.partials.header')

        <!-- Contenu principal -->
        <div class="main-container">
            <!-- Fond décoratif -->
            <div class="background-decor"></div>

            <!-- Contenu -->
            <div class="content">
                <!-- Logo et titre -->
                <div class="brand-header">
                    <div class="logo-container">
                        <div class="logo-circle">
                            <span class="logo-text">A</span>
                            <div class="logo-arc red"></div>
                            <div class="logo-arc yellow"></div>
                            <div class="logo-arc green"></div>
                        </div>
                    </div>
                    <h1 class="main-title">ARCOP <span>MAN</span></h1>
                    <p class="tagline">Votre portail d'applications intégré</p>
                </div>

                <!-- Barre de recherche -->
                <div class="search-wrapper">
                    <div class="search-container">
                        <i class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </i>
                        <input type="text" placeholder="Rechercher une application..." class="search-input">
                    </div>
                </div>

                <!-- Grille d'applications -->
                <div class="apps-grid">
                    @canany(['access-un-all', 'access-un-opti-hr'])
                        <!-- OptiHR App -->
                        <a href="{{ route('opti-hr.home') }}" class="app-card">
                            <div class="app-icon-wrapper">
                                <div class="app-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32"
                                        height="32" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="app-content">
                                <h3>OptiHR</h3>
                                <p>Gestion des ressources humaines</p>
                            </div>
                            <div class="app-accent red"></div>
                        </a>
                    @endcanany

                    @canany(['access-un-all', 'access-un-recours'])
                        <!-- Recours App -->
                        <a href="{{ route('recours.home') }}" class="app-card">
                            <div class="app-icon-wrapper">
                                <div class="app-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32"
                                        height="32" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                        <path d="M8 16H6v-2h2m4 2h-2v-2h2m4 2h-2v-2h2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="app-content">
                                <h3>Recours</h3>
                                <p>Système de rappels et suivi</p>
                            </div>
                            <div class="app-accent yellow"></div>
                        </a>
                    @endcanany
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>

    <style>
        /* Variables de couleurs ARCOP */
        :root {
            --arcop-green: #37a045;
            --arcop-green-light: #b5d8b7;
            --arcop-green-dark: #2a7b33;
            --arcop-red: #e73137;
            --arcop-yellow: #ffd700;
            --white: #ffffff;
            --gray-light: #f8f9fa;
            --gray: #e9ecef;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --transition: all 0.3s ease;
        }

        /* Reset et base */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            background-color: var(--gray-light);
            overflow-x: hidden;
        }

        /* Structure principale */
        .app-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-container {
            flex: 1;
            position: relative;
            padding: 2rem 1rem;
            overflow: hidden;
        }

        /* Fond décoratif avec les couleurs ARCOP */
        .background-decor {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(ellipse at center, var(--arcop-green-light) 0%, transparent 70%),
                radial-gradient(circle at 10% 90%, rgba(231, 49, 55, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 10%, rgba(255, 215, 0, 0.1) 0%, transparent 40%);
            background-color: var(--gray-light);
        }

        /* Contenu central */
        .content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* En-tête de marque */
        .brand-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .logo-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin-bottom: 1rem;
        }

        .logo-circle {
            width: 100%;
            height: 100%;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .logo-text {
            font-size: 3rem;
            font-weight: bold;
            color: var(--arcop-green);
            z-index: 2;
        }

        .logo-arc {
            position: absolute;
            border-radius: 50%;
            border-style: solid;
            border-width: 3px;
            width: 140%;
            height: 140%;
            top: -20%;
            left: -20%;
            border-bottom-color: transparent;
            border-left-color: transparent;
            border-right-color: transparent;
            transform: rotate(0deg);
            animation: rotate 20s linear infinite;
        }

        .logo-arc.red {
            border-top-color: var(--arcop-red);
            animation-duration: 15s;
            width: 130%;
            height: 130%;
            top: -15%;
            left: -15%;
        }

        .logo-arc.yellow {
            border-top-color: var(--arcop-yellow);
            animation-duration: 20s;
            animation-direction: reverse;
            width: 120%;
            height: 120%;
            top: -10%;
            left: -10%;
        }

        .logo-arc.green {
            border-top-color: var(--arcop-green);
            animation-duration: 25s;
            width: 110%;
            height: 110%;
            top: -5%;
            left: -5%;
        }

        .main-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--arcop-green);
            margin: 0.5rem 0;
            letter-spacing: -1px;
        }

        .main-title span {
            font-weight: 300;
        }

        .tagline {
            font-size: 1.2rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        /* Barre de recherche */
        .search-wrapper {
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        .search-container {
            background-color: white;
            border-radius: 50px;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .search-container:hover,
        .search-container:focus-within {
            box-shadow: var(--shadow-lg);
            border-color: var(--arcop-green-light);
            transform: translateY(-2px);
        }

        .search-icon {
            color: var(--arcop-green);
            margin-right: 0.8rem;
        }

        .search-input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
            background: transparent;
        }

        /* Grille d'applications */
        .apps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 1rem 0;
        }

        /* Carte d'application */
        .app-card {
            background-color: white;
            border-radius: var(--radius-md);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            position: relative;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 160px;
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        .app-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Animation séquentielle des cartes */
        .app-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .app-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .app-icon-wrapper {
            display: flex;
            justify-content: center;
            padding: 1.5rem 1rem 0.5rem;
        }

        .app-icon {
            width: 60px;
            height: 60px;
            background-color: var(--gray-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--arcop-green);
            transition: var(--transition);
        }

        .app-card:hover .app-icon {
            transform: scale(1.1) translateY(-5px);
            color: white;
            background-color: var(--arcop-green);
        }

        .app-content {
            padding: 1rem 1.5rem 1.5rem;
            flex-grow: 1;
        }

        .app-content h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0 0 0.5rem;
            color: var(--text-dark);
        }

        .app-content p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-light);
            line-height: 1.4;
        }

        /* Accent de couleur sur les cartes */
        .app-accent {
            height: 4px;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
            background-color: var(--arcop-green);
        }

        .app-accent.red {
            background-color: var(--arcop-red);
        }

        .app-accent.yellow {
            background-color: var(--arcop-yellow);
        }

        .app-accent.green {
            background-color: var(--arcop-green);
        }

        /* Header styles */
        .arcop-header {
            background-color: var(--white);
            border-bottom: 1px solid var(--gray);
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1.5rem;
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        .brand-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .brand-link:hover {
            color: var(--arcop-green);
        }

        .brand-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            color: var(--arcop-green);
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .user-section {
            display: flex;
            align-items: center;
        }

        .user-info {
            text-align: right;
            margin-right: 0.75rem;
        }

        .user-name {
            margin: 0;
            font-weight: 600;
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-light);
            display: block;
        }

        .avatar-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background-color: var(--arcop-green-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--arcop-green);
            transition: var(--transition);
        }

        .user-avatar:hover {
            background-color: var(--arcop-green);
            color: var(--white);
        }

        .dropdown-menu {
            padding: 0;
            overflow: hidden;
            border: none;
            border-radius: 0.5rem;
            width: 280px;
            animation: fadeInDown 0.3s ease forwards;
        }

        .dropdown-header {
            background-color: var(--gray-light);
            padding: 1rem;
            border-bottom: 1px solid var(--gray);
        }

        .header-username {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }

        .header-email {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .dropdown-divider {
            margin: 0;
            border-color: var(--gray);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(55, 160, 69, 0.08);
            color: var(--arcop-green);
        }

        .dropdown-item svg {
            margin-right: 0.75rem;
        }

        /* Animations */
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

        /* Responsive */
        @media (max-width: 768px) {
            .apps-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
            }

            .main-title {
                font-size: 2rem;
            }

            .app-card {
                min-height: 140px;
            }
        }

        @media (max-width: 576px) {
            .apps-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .logo-container {
                width: 80px;
                height: 80px;
            }

            .logo-text {
                font-size: 2.5rem;
            }

            .app-icon {
                width: 50px;
                height: 50px;
            }

            .app-content h3 {
                font-size: 1.1rem;
            }

            .app-content p {
                font-size: 0.8rem;
            }

            .user-info {
                display: none;
            }

            .header-container {
                padding: 0 1rem;
            }

            .brand-text {
                font-size: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation d'affichage séquentiel des cartes
            const appCards = document.querySelectorAll('.app-card');

            appCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 * (index + 1));
            });

            // Fonctionnalité de recherche
            const searchInput = document.querySelector('.search-input');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                appCards.forEach(card => {
                    const title = card.querySelector('h3').textContent.toLowerCase();
                    const description = card.querySelector('p').textContent.toLowerCase();

                    if (title.includes(searchTerm) || description.includes(searchTerm)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>
