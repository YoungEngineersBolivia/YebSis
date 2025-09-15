<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Young Engineers La Paz - STEM para Niños</title>
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">

</head>

<style>
.modal-overlay {
  display: none; /* oculto por defecto */
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-overlay.show {
  display: flex; /* se muestra cuando agregas la clase show */
}

.modal-container {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  width: 400px;
  max-width: 90%;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.modal-header {
  text-align: center;
  margin-bottom: 20px;
}

.close-btn {
  float: right;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
}
</style>
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
    <h1 class="hero-title">SOBRE NOSOTROS</h1> <!-- centrado -->

    <div class="hero-container">
        <!-- Columna 1: Texto -->
        <div class="contenedor-texto">
            <p >
                Hola amigos, bienvenidos a la página de Young Engineers para la zona sur de La Paz. Gracias por visitarnos en este sitio web. Young Engineers, Zona Sur, La Paz, es un Centro de Estudios que da a los niños de nuestra comunidad la posibilidad de aprender y disfrutar del <span class="highlight">STEM</span> (siglas en inglés para Ciencias, Tecnología, Ingeniería y Matemáticas) a través del modelo que une el aprender con la diversión: <span class="highlight">EDUTAINMENT</span>.
            </p>
            <p>
                Las carreras de tecnología, ingeniería y ciencia son el futuro y nosotros queremos que los niños de nuestra comunidad puedan acercarse a estas disciplinas de una manera práctica y divertida. ¡Únete a nuestra comunidad y empecemos juntos el cambio hacia el futuro!
            </p>
        </div>

        <!-- Columna 2: Imagen -->
        <div class="hero-visual">
            <img src="{{ Vite::asset('resources/img/Logo_sign.png') }}" alt="Logo YE Bolivia" width="250px" class="me-2">
        </div>

        <!-- Columna 3: Contáctanos -->
        <div class="contact-section">
            <h3 style="color: black; margin-bottom: 10px;">¡CONTÁCTANOS!</h3>
            <div class="contact-buttons">
                <!-- Botón con animación JSON -->
                <a href="#" class="btn-contact btn-whatsapp">
                    <div id="whatsapp-animation" style="width:40px; height:40px; display:inline-block; vertical-align:middle;"></div>
                    <span style="vertical-align:middle; margin-left:8px;">WHATSAPP</span>
                </a>

                <p><b>O</b></p>

                <!-- Botón normal -->
                <!-- Botón normal -->
                `<a href="#" class="btn-contact btn-register" onclick="openModal()">
                    <img src="{{ Vite::asset('resources/img/registro.png') }}" alt="Registro">
                    <span>Estoy interesado</span>
                </a>`

                <div class="modal-overlay" id="contactModal" onclick="closeModalOnOverlay(event)">
                    <div class="modal-container">
                        <!-- Header -->
                        <div class="modal-header">
                            <button class="close-btn" onclick="closeModal()">&times;</button>
                            <h2 class="modal-title">CONTÁCTANOS</h2>
                            <p class="modal-subtitle">
                                Por favor llene sus datos y nosotros nos contactaremos con usted a la brevedad posible
                            </p>
                        </div>

                        <!-- Body -->
                        <div class="modal-body">
                            <form id="contactForm">
                                <div class="form-group">
                                    <label class="form-label">Nombres</label>
                                    <input type="text" class="form-input" name="nombres" placeholder="Ingrese sus nombres" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" class="form-input" name="apellidos" placeholder="Ingrese sus apellidos" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Numero de celular para que podamos contactarte</label>
                                    <input type="tel" class="form-input" name="telefono" placeholder="Ingrese su número de teléfono" required>
                                </div>

                                <button type="submit" class="submit-btn">ENVIAR INFORMACIÓN</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




        <!-- Programs Section -->
        <section class="programs">
            <h2>PROGRAMAS</h2>
            <div class="programs-grid">
    @foreach($programas as $programa)
        <div class="program-card">
            <div class="program-icon">
                @if($programa->Foto)
                    <img src="data:image/jpeg;base64,{{ base64_encode($programa->Foto) }}" 
                         alt="{{ $programa->Nombre }}" style="width:150px; height:150px;">
                @else
                    🏫
                @endif
            </div>
            <h3 class="program-title">{{ $programa->Nombre }}</h3>
            <div class="program-details">
                <div class="age-badge">Edad: {{ $programa->Rango_edad }}</div><br>
                <div class="duration-badge">Duración: {{ $programa->Duracion }}</div>
                <div class="cost-badge">Costo: ${{ $programa->Costo }}</div>
            </div>
        </div>
    @endforeach
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
        
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <div>
                        <img src="{{ Vite::asset('resources/img/logo_blanco.png') }}" alt="Logo YE Bolivia" width="250px" class="me-2">
                          <p>© Copyright 2020 e Square Young Engineers Franchising Ltd. Todos los derechos reservados.</p>
                           <p>LEGO® es una marca registrada de empresas que no patrocinan, autorizan ni respaldan estos programas o este sitio web.</p>
                    </div>
                    
                    <div class="footer-text">
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
    <script>
function openModal() {
    const modal = document.getElementById('contactModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('contactModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function closeModalOnOverlay(event) {
    if (event.target === event.currentTarget) {
        closeModal();
    }
}

// cerrar con Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// manejar envío del formulario
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    alert(`¡Gracias ${formData.get('nombres')} ${formData.get('apellidos')}! Nos contactaremos contigo al ${formData.get('telefono')} pronto.`);
    this.reset();
    closeModal();
});
</script>

</body>


</html>