<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail d'Applications</title>
    <link rel="stylesheet" href="{{ asset('assets/css/my-task.style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/gateway.css') }}">
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Main Content -->

            @include('modules.gateway.partials.header')
            <!-- Content Area -->
            <div class="main-content p-4">
                <h1 class="page-title">Vos Applications</h1>

                <div class="search-container d-flex justify-content-center mb-5 mx-auto">
                    <div class="search-bar d-flex align-items-center w-100">
                        <span class="flex-grow-1 text-muted">Rechercher une application...</span>
                        <i class="icofont-search"></i>
                    </div>
                </div>

                <!-- Applications Grid -->
                <div class="app-grid">

                    @canany(['access-all', 'access-opti-rh'])
                        <!-- OptiHR App -->
                        <a href="{{ route('opti-hr.home') }}" class="app-card">
                            <div class="app-logo">
                                <img src="{{ asset('assets/img/optihr-logo.png') }}" alt="OptiHR" />
                            </div>
                            <h3 class="app-title">OptiHR</h3>
                            <p class="app-description">Gestion des ressources humaines</p>
                        </a>
                    @endcanany
                    @canany(['access-all', 'access-recours'])
                        <!-- Recours App -->
                        <a href="{{ route('recours.home') }}" class="app-card">
                            <div class="app-logo">
                                <img src="{{ asset('assets/img/recours-logo.png') }}" alt="Recours" />
                            </div>
                            <h3 class="app-title">Recours</h3>
                            <p class="app-description">Système de rappels et suivi</p>
                        </a>
                    @endcanany

                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
    <!-- Animation des particules en arrière-plan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Créer un canvas pour les particules
            const particleCanvas = document.createElement('canvas');
            particleCanvas.style.position = 'absolute';
            particleCanvas.style.top = '0';
            particleCanvas.style.left = '0';
            particleCanvas.style.width = '100%';
            particleCanvas.style.height = '100%';
            particleCanvas.style.pointerEvents = 'none';
            particleCanvas.style.zIndex = '0';
            document.querySelector('.main-content').prepend(particleCanvas);

            // Configurer le canvas
            const ctx = particleCanvas.getContext('2d');
            particleCanvas.width = window.innerWidth;
            particleCanvas.height = window.innerHeight;

            // Paramètres des particules
            const particlesArray = [];
            const numberOfParticles = 50; // Augmenté pour plus d'effet

            // Classe Particule
            class Particle {
                constructor() {
                    this.x = Math.random() * particleCanvas.width;
                    this.y = Math.random() * particleCanvas.height;
                    this.size = Math.random() * 5 + 1;
                    this.speedX = Math.random() * 1 - 0.5;
                    this.speedY = Math.random() * 1 - 0.5;
                    this.color = 'rgba(255, 255, 255, ' + (Math.random() * 0.3 + 0.2) + ')';
                }

                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;

                    if (this.size > 0.2) this.size -= 0.01;

                    if (this.x < 0 || this.x > particleCanvas.width) {
                        this.speedX = -this.speedX;
                    }

                    if (this.y < 0 || this.y > particleCanvas.height) {
                        this.speedY = -this.speedY;
                    }
                }

                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            // Créer les particules
            function init() {
                particlesArray.length = 0;
                for (let i = 0; i < numberOfParticles; i++) {
                    particlesArray.push(new Particle());
                }
            }

            // Animer les particules
            function animate() {
                ctx.clearRect(0, 0, particleCanvas.width, particleCanvas.height);

                for (let i = 0; i < particlesArray.length; i++) {
                    particlesArray[i].update();
                    particlesArray[i].draw();
                }

                // Connecter les particules proches
                connectParticles();
                requestAnimationFrame(animate);
            }

            // Connecter les particules avec des lignes
            function connectParticles() {
                for (let a = 0; a < particlesArray.length; a++) {
                    for (let b = a; b < particlesArray.length; b++) {
                        const dx = particlesArray[a].x - particlesArray[b].x;
                        const dy = particlesArray[a].y - particlesArray[b].y;
                        const distance = Math.sqrt(dx * dx + dy * dy);

                        if (distance < 120) { // Augmenté pour plus de connexions
                            ctx.strokeStyle = 'rgba(255, 255, 255, ' + (0.2 - distance / 500) + ')';
                            ctx.lineWidth = 1;
                            ctx.beginPath();
                            ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                            ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                            ctx.stroke();
                        }
                    }
                }
            }

            // Redimensionner le canvas si la fenêtre change
            window.addEventListener('resize', function() {
                particleCanvas.width = window.innerWidth;
                particleCanvas.height = window.innerHeight;
                init();
            });

            // Initialiser et démarrer l'animation
            init();
            animate();

            // Animation pour la barre de recherche
            const searchBar = document.querySelector('.search-bar');
            searchBar.addEventListener('click', function() {
                this.innerHTML =
                    '<input type="text" class="border-0 w-100 bg-transparent" placeholder="Rechercher une application..." autofocus>';
                this.querySelector('input').focus();
            });
        });
    </script>
</body>

</html>
