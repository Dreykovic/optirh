<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail</title>
    <link rel="stylesheet" href="{{ asset('assets/css/my-task.style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/gateway.css') }}">

</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Main Content -->
            <div class="col
                <!-- Browser Nav -->
                <div class="browser-window">
                <div class="address-bar d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm me-1"><i class="icofont-arrow-left"></i></button>
                        <button class="btn btn-sm me-1"><i class="icofont-arrow-right"></i></button>
                        <button class="btn btn-sm me-1"><i class="icofont-refresh"></i></button>
                        <span class="border rounded px-3 py-1 text-muted">Enter search or web address</span>
                    </div>
                    <div>
                        <button class="btn btn-sm"><i class="icofont-share"></i></button>
                        <button class="btn btn-sm"><i class="icofont-camera"></i></button>
                        <button class="btn btn-sm"><i class="icofont-ui-grid"></i></button>
                        <button class="btn btn-sm"><i class="icofont-user-alt-7"></i></button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="main-content p-4">
                <div class="search-container d-flex justify-content-center mb-5 mx-auto" style="max-width: 600px;">
                    <div class="search-bar d-flex align-items-center w-100">
                        <img src="/api/placeholder/20/20" alt="Google" class="me-2" />
                        <span class="flex-grow-1 text-muted">Search the web</span>
                        <i class="icofont-search"></i>
                    </div>
                </div>

                <!-- Shortcuts -->
                <div class="d-flex justify-content-center flex-wrap" style="max-width: 800px; margin: 0 auto;">
                    <div class="shortcut-container text-center">
                        <img src="/api/placeholder/30/30" alt="Medium" class="mb-2" />
                        <span class="small">Medium</span>
                    </div>
                    <div class="shortcut-container text-center">
                        <img src="/api/placeholder/30/30" alt="Twitch" class="mb-2" />
                        <span class="small">Twitch</span>
                    </div>
                    <div class="shortcut-container text-center">
                        <img src="/api/placeholder/30/30" alt="Reddit" class="mb-2" />
                        <span class="small">Reddit</span>
                    </div>
                    <div class="shortcut-container text-center">
                        <img src="/api/placeholder/30/30" alt="Twitter" class="mb-2" />
                        <span class="small">Twitter</span>
                    </div>
                    <div class="shortcut-container text-center">
                        <img src="/api/placeholder/30/30" alt="Airbnb" class="mb-2" />
                        <span class="small">Airbnb</span>
                    </div>
                    <div class="shortcut-container text-center">
                        <img src="/api/placeholder/30/30" alt="Youtube" class="mb-2" />
                        <span class="small">Youtube</span>
                    </div>
                    <div class="shortcut-container text-center">
                        <img src="/api/placeholder/30/30" alt="Netflix" class="mb-2" />
                        <span class="small">Netflix</span>
                    </div>
                    <div class="shortcut-container text-center">
                        <span class="add-button">+</span>
                        <span class="small">Add a site</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


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
            const numberOfParticles = 30;

            // Classe Particule
            class Particle {
                constructor() {
                    this.x = Math.random() * particleCanvas.width;
                    this.y = Math.random() * particleCanvas.height;
                    this.size = Math.random() * 3 + 1;
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

                        if (distance < 100) {
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
        });
    </script>
</body>

</html>
