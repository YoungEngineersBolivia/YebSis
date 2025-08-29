<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Young Engineers La Paz - STEM para Niños</title>
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">

</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="{{ Vite::asset('resources/img/ES_logo-02.webp') }}"alt="Logo YE Bolivia" width="250px" class="me-2">
                </div>
                <nav class="nav-links">
                    <span>RECONOCIDO POR:</span>
                    <img src="{{Vite::asset('resources/img/recognized_by.png')}}" alt="Reconocimiento" width="200px" class="me-2">
                    <span>REDES SOCIALES</span>
                    <a href="{{ route('login') }}" class="btn-iniciar">Iniciar Sesión</a>

                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Hero Section -->
        <section class="hero">
           
               
                    <h1>SOBRE NOSOTROS</h1>
                    <p>
                        Hola amigos, bienvenidos a la página de Young Engineers para la zona sur de La Paz. Gracias por visitarnos en este sitio web. Young Engineers, Zona Sur, La Paz, es un Centro de Estudios que da a los niños de nuestra comunidad la posibilidad de aprender y disfrutar del <span class="highlight">STEM</span> (siglas en inglés para Ciencias, Tecnología, Ingeniería y Matemáticas) a través del modelo que une el aprender con la diversión: <span class="highlight">EDUTAINMENT</span>.
                    </p>
                    <p>
                        Las carreras de tecnología, ingeniería y ciencia son el futuro y nosotros queremos que los niños de nuestra comunidad puedan acercarse a estas disciplinas de una manera práctica y divertida. ¡Únete a nuestra comunidad y empecemos juntos el cambio hacia el futuro!
                    </p>
               
                <div class="hero-visual">
                    <img src="{{ Vite::asset('resources/img/Logo_sign.png') }}"alt="Logo YE Bolivia" width="250px" class="me-2">
                </div>
            
            
            <div class="contact-section">
                <h3 style="color: white; margin-bottom: 10px;">¡CONTÁCTANOS!</h3>
                <div class="contact-buttons">

                    <!-- Botón con animación JSON -->
                    <a href="#" class="btn-contact btn-whatsapp">
                        <div id="whatsapp-animation" style="width:40px; height:40px; display:inline-block; vertical-align:middle;"></div>
                        <span style="vertical-align:middle; margin-left:8px;">WHATSAPP</span>
                    </a>

                    <!-- Botón normal -->
                    <a href="#" class="btn-contact btn-register">
                        <img src="{{ Vite::asset('resources/img/registro.png') }}" alt="Registro">
                        <span>REGISTRARSE</span>
                    </a>

                </div>
            </div>
        </section>

        <!-- Programs Section -->
        <section class="programs">
            <h2>PROGRAMAS</h2>
            <div class="programs-grid">
                <div class="program-card big-builders">
                    <div class="program-icon">👥</div>
                    <h3 class="program-title">BIG BUILDERS</h3>
                    <div class="program-details">
                        <div class="age-badge">Edad: 4-6</div><br>
                        <div class="duration-badge">Duración: 60 Minutos</div>
                    </div>
                </div>

                <div class="program-card galileo">
                    <div class="program-icon">🔬</div>
                    <h3 class="program-title">GALILEO TECHNIC</h3>
                    <div class="program-details">
                        <div class="age-badge">Edad: 6-10</div><br>
                        <div class="duration-badge">Duración: 75 Minutos</div>
                    </div>
                </div>

                <div class="program-card bricks">
                    <div class="program-icon">🧱</div>
                    <h3 class="program-title">BRICKS CHALLENGE</h3>
                    <div class="program-details">
                        <div class="age-badge">Edad: 7-10</div><br>
                        <div class="duration-badge">Duración: 75 Minutos</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- News Section -->
        <section class="news">
            <h2>NOVEDADES</h2>
            <div class="news-grid">
                <div class="news-card">
                    <div class="news-image">
                        <div style="font-size: 60px;">🎮</div>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">ALGO PLAY</h3>
                        <p class="news-description">
                            Descubre nuestro nuevo programa de programación y algoritmos diseñado para introducir a los niños en el mundo de la codificación de manera divertida y educativa.
                        </p>
                    </div>
                </div>

                <div class="news-card">
                    <div class="news-image">
                        <div style="font-size: 60px;">🏗️</div>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">NUEVOS MODELOS</h3>
                        <p class="news-description">
                            Presentamos nuestra nueva colección de modelos LEGO® y kits de construcción que permitirán a los estudiantes explorar conceptos de ingeniería avanzada.
                        </p>
                    </div>
                </div>
            </div>

            <div class="winter-workshop">
                <h3>INSCRÍBETE AL TALLER DE INVIERNO</h3>
                <p>¡No te pierdas nuestro taller especial de vacaciones de invierno!</p>
                <button class="btn-contact" style="margin-top: 20px; background: linear-gradient(45deg, #00b894, #00a085);">Más Información</button>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <div class="footer-logo-circle">e</div>
                    <div class="footer-text">
                        <h3>JÓVENES INGENIEROS</h3>
                        <p>© Copyright 2020 e Square Young Engineers Franchising Ltd. Todos los derechos reservados.</p>
                        <p>LEGO® es una marca registrada de empresas que no patrocinan, autorizan ni respaldan estos programas o este sitio web.</p>
                        <div class="social-icons">
                            <a href="#" class="social-icon">f</a>
                            <a href="#" class="social-icon">▶</a>
                        </div>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Young Engineers</h3>
                    <p style="margin-top: 20px; color: #bdc3c7;">Programa educativo líder en STEM para niños y jóvenes.</p>
                </div>

                <div class="contact-info">
                    <h3>Contact Info</h3>
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <p>Calle Ignacio Cordero, Edif. Torre Montenegro, Piso 3, Oficina 404</p>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">✉</div>
                        <p>info.zonasurlapaz@e2youngengineers.com</p>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📱</div>
                        <p>móvil number: +591 77788398</p>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>Young Engineers La Paz - Zona Sur | Educación STEM para el futuro</p>
            </div>
        </div>
    </footer>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.6/lottie.min.js"></script>

    <script>
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);

        // Dynamic floating elements
        function createFloatingElement() {
            const element = document.createElement('div');
            element.className = 'floating-element';
            element.style.left = Math.random() * 100 + '%';
            element.style.animationDelay = Math.random() * 2 + 's';
            element.style.animationDuration = (15 + Math.random() * 10) + 's';
            
            const colors = ['rgba(255, 255, 255, 0.1)', 'rgba(78, 205, 196, 0.1)', 'rgba(255, 107, 107, 0.1)'];
            element.style.background = colors[Math.floor(Math.random() * colors.length)];
            
            document.querySelector('.floating-elements').appendChild(element);
            
            setTimeout(() => {
                element.remove();
            }, 25000);
        }

        // Create floating elements periodically
        setInterval(createFloatingElement, 3000);


        lottie.loadAnimation({
        container: document.getElementById('whatsapp-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: "{{ asset('animaciones/whatsapp.json') }}" // tu JSON
    });
    </script>

</body>


</html>