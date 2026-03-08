<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Young Engineers La Paz - STEM para Niños</title>
    <link href="<?php echo e(auto_asset('css/paginaWeb/home.css')); ?>" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="<?php echo e(auto_asset('img/ES_logo-02.webp')); ?>" alt="Logo YE Bolivia" width="250px" class="me-2">
                </div>
                <button class="navbar-toggle" onclick="toggleNavbar()">&#9776;</button>
                <nav class="nav-links" id="mainNavLinks">
                    <span>RECONOCIDO POR:</span>
                    <img src="<?php echo e(auto_asset('img/recognized_by.png')); ?>" alt="Reconocimiento" width="200px" class="me-2">
                    <div class="social-icons" style="margin-top: -10px;">
                        <a href="https://www.facebook.com/youngengineerszonasurlapaz/" class="social-icon"
                            target="_blank">
                            <img src="<?php echo e(asset('img/facebook.svg')); ?>" alt="Facebook" width="54" height="54">
                        </a>
                        <a href="https://www.tiktok.com/@youngengineersbolivia" class="social-icon" target="_blank">
                            <img src="<?php echo e(asset('img/tiktok.svg')); ?>" alt="tiktok" width="45" height="45">

                        </a>
                    </div>

                    <a href="<?php echo e(route('login')); ?>" class="btn-iniciar">Iniciar Sesión</a>
                </nav>
            </div>
        </div>
    </header>
    <script>
        function toggleNavbar() {
            var nav = document.getElementById('mainNavLinks');
            nav.classList.toggle('show');
        }
    </script>

    <div class="container">
        <!-- Hero Section -->
        <section class="hero">
            <h1 class="hero-title">SOBRE NOSOTROS</h1>
            <div class="hero-container">
                <!-- Columna 1: Texto -->
                <div class="contenedor-texto">
                    <p>
                        Hola amigos, bienvenidos a la página de Young Engineers para la zona sur de La Paz. Gracias por
                        visitarnos en este sitio web. Young Engineers, Zona Sur, La Paz, es un Centro de Estudios que da
                        a los niños de nuestra comunidad la posibilidad de aprender y disfrutar del <span
                            class="highlight">STEM</span> (siglas en inglés para Ciencias, Tecnología, Ingeniería y
                        Matemáticas) a través del modelo que une el aprender con la diversión: <span
                            class="highlight">EDUTAINMENT</span>.
                    </p>
                    <p>
                        Las carreras de tecnología, ingeniería y ciencia son el futuro y nosotros queremos que los niños
                        de nuestra comunidad puedan acercarse a estas disciplinas de una manera práctica y divertida.
                        ¡Únete a nuestra comunidad y empecemos juntos el cambio hacia el futuro!
                    </p>
                </div>

                <!-- Columna 2: Imagen -->
                <div class="hero-visual">
                    <img src="<?php echo e(auto_asset('img/Logo_sign.png')); ?>" alt="Logo YE Bolivia" width="250px" class="me-2">
                </div>

                <!-- Columna 3: Contáctanos -->
                <div class="contact-section">
                    <h3 style="color: black; margin-bottom: 10px;">¡CONTÁCTANOS!</h3>
                    <div class="contact-buttons">
                        <a href="https://wa.me/59177788398" target="_blank" class="btn-contact btn-whatsapp">
                            <div id="whatsapp-animation"
                                style="width:40px; height:40px; display:inline-block; vertical-align:middle;"></div>
                            <span style="vertical-align:middle; margin-left:8px;">WHATSAPP</span>
                        </a>
                        <p><b>O</b></p>
                        <a href="#" class="btn-contact btn-register" onclick="openContactModal()">
                            <img src="<?php echo e(auto_asset('img/registro.png')); ?>" alt="Registro">
                            <span>Estoy interesado</span>
                        </a>

                        <!-- MODAL DE CONTACTO -->
                        <div class="modal-overlay" id="contactModal" onclick="closeContactModalOnOverlay(event)">
                            <div class="modal-container">
                                <div class="modal-header">
                                    <button class="close-btn" onclick="closeContactModal()">&times;</button>
                                    <h2 class="modal-title">CONTÁCTANOS</h2>
                                    <p class="modal-subtitle">
                                        Por favor llene sus datos y nosotros nos contactaremos con usted a la brevedad
                                        posible
                                    </p>
                                </div>

                                <?php if(session('success')): ?>
                                    <div style="color:green; font-weight:bold; margin-bottom:10px;">
                                        <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>

                                <div class="modal-body">
                                    <form id="contactForm" action="<?php echo e(route('prospectos.store')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label>Nombres</label>
                                            <span class="input-icon">👤</span>
                                            <input type="text" name="nombres" placeholder="Ingrese sus nombres"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Apellidos</label>
                                            <span class="input-icon">👥</span>
                                            <input type="text" name="apellidos" placeholder="Ingrese sus apellidos"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <span class="input-icon">📱</span>
                                            <input type="tel" name="telefono"
                                                placeholder="Ingrese su número de teléfono" required>
                                        </div>
                                        <button type="submit">ENVIAR INFORMACIÓN</button>
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
            <h2 class="section-title programas">PROGRAMAS</h2>
            <div class="programs-grid">
                <?php $colors = ['#e74c3c', '#f9ca24', '#0984e3', '#00b894'];
                $i = 0; ?>
                <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($programa->Tipo === 'programa'): ?>
                        <?php $bgColor = $colors[$i % 4];
                        $i++; ?>
                        <div class="program-card"
                            style="background: #ffffff; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-top: 6px solid <?php echo e($bgColor); ?>;">
                            <div class="program-icon" style="text-align:center; margin-bottom:10px;">
                                <?php if($programa->Imagen): ?>
                                    <img src="<?php echo e(auto_asset('storage/' . $programa->Imagen)); ?>" alt="<?php echo e($programa->Nombre); ?>"
                                        style="max-width:150px; height:auto;">
                                <?php else: ?>
                                    <img src="<?php echo e(auto_asset('img/Logo_sign.png')); ?>" alt="Imagen por defecto"
                                        style="max-width:80px; height:auto; margin:12px 0;">
                                <?php endif; ?>
                            </div>
                            <h3 class="program-title" style="color: <?php echo e($bgColor); ?>;"><?php echo e($programa->Nombre); ?></h3>
                            <div class="program-details">
                                <div class="age-badge">Edad: <?php echo e($programa->Rango_edad); ?></div>
                                <div class="duration-badge">Duración: <?php echo e($programa->Duracion); ?></div>
                                <div class="cost-badge">Costo: Bs<?php echo e($programa->Costo); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>


        <!-- Talleres Section -->
        <section class="programs" style="margin-top: 32px;">
            <h2 class="section-title talleres">TALLERES</h2>
            <div class="programs-grid">
                <?php $colors = ['#e74c3c', '#f9ca24', '#0984e3', '#00b894', '#8e44ad', '#fd79a8', '#00bfff'];
                $j = 0; ?>
                <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($programa->Tipo === 'taller'): ?>
                        <?php $bgColor = $colors[$j % count($colors)];
                        $j++; ?>
                        <div class="program-card"
                            style="background: #ffffff; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-top: 6px solid <?php echo e($bgColor); ?>;">
                            <div class="program-icon" style="text-align:center; margin-bottom:10px;">
                                <?php if($programa->Imagen): ?>
                                    <img src="<?php echo e(auto_asset('storage/' . $programa->Imagen)); ?>" alt="<?php echo e($programa->Nombre); ?>"
                                        style="max-width:150px; height:auto;">
                                <?php else: ?>
                                    <img src="<?php echo e(auto_asset('img/Logo_sign.png')); ?>" alt="Imagen por defecto"
                                        style="max-width:80px; height:auto; margin:12px 0;">
                                <?php endif; ?>
                            </div>
                            <h3 class="program-title" style="color: <?php echo e($bgColor); ?>;"><?php echo e($programa->Nombre); ?></h3>
                            <div class="program-details">
                                <div class="age-badge">Edad: <?php echo e($programa->Rango_edad); ?></div>
                                <div class="duration-badge">Duración: <?php echo e($programa->Duracion); ?></div>
                                <div class="cost-badge">Costo: Bs<?php echo e($programa->Costo); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
        </section>
    </div>



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
                        Descubre nuestro nuevo programa de programación y algoritmos diseñado para introducir a los
                        niños en el mundo de la codificación de manera divertida y educativa.
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
                        Presentamos nuestra nueva colección de modelos LEGO® y kits de construcción que permitirán a los
                        estudiantes explorar conceptos de ingeniería avanzada.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MODAL PUBLICACIONES -->
    <div class="modal-overlay" id="publicacionesModal" onclick="closePublicacionesModalOnOverlay(event)">
        <div class="modal-container">
            <div class="modal-header">
                <button class="close-btn" onclick="closePublicacionesModal()">&times;</button>
                <h2 class="modal-title"></h2>
            </div>
            <div class="modal-body" style="max-height:80%; overflow-y:auto;">
                <?php if(isset($publicaciones) && $publicaciones->count() > 0): ?>
                    <?php $__currentLoopData = $publicaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publicacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card"
                            style="margin-bottom:15px; border:1px solid #ddd; border-radius:8px; padding:15px; background: #f9f9f9;">
                            <?php if($publicacion->Imagen): ?>
                                <img src="<?php echo e(auto_asset('storage/' . $publicacion->Imagen)); ?>"
                                    alt="Imagen de <?php echo e($publicacion->Nombre); ?>"
                                    style="width:100%; max-height:500px; object-fit:cover; border-radius:8px;">
                            <?php else: ?>
                                <p>No hay imagen disponible</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px;">
                        <h3>📭 No hay publicaciones disponibles</h3>
                        <p>Por el momento no tenemos novedades que mostrar.</p>
                        <p style="font-size: 12px; color: #666; margin-top: 20px;">
                            <strong>Info para desarrollador:</strong><br>
                            Variable $publicaciones: <?php echo e(isset($publicaciones) ? 'Existe' : 'No existe'); ?><br>
                            Cantidad: <?php echo e($publicaciones->count() ?? 0); ?>

                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <div>
                        <img src="<?php echo e(auto_asset('img/logo_blanco.png')); ?>" alt="Logo YE Bolivia" width="250px"
                            class="me-2">
                        <p>© Copyright 2020 e Square Young Engineers Franchising Ltd. Todos los derechos reservados.</p>
                        <p>LEGO® es una marca registrada de empresas que no patrocinan, autorizan ni respaldan estos
                            programas o este sitio web.</p>
                    </div>
                    <div class="footer-text">
                        <div class="social-icons">
                            <a href="https://www.facebook.com/youngengineerszonasurlapaz/" class="social-icon">
                                <img src="<?php echo e(asset('img/facebook.svg')); ?>" alt="Facebook" width="54" height="54">
                            </a>
                            <a href="#" class="social-icon">
                                <img src="<?php echo e(asset('img/tiktok.svg')); ?>" alt="tiktok" width="45" height="45">

                            </a>
                        </div>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Young Engineers</h3>
                    <p style="margin-top: 20px; color: #bdc3c7;">Programa educativo líder en STEM para niños y jóvenes.
                    </p>
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
        <div class="floating-elements"></div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.6/lottie.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.6/lottie.min.js"></script>
    <script>
        // ========== ANIMACIONES Y ELEMENTOS FLOTANTES ==========
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        function createFloatingElement() {
            const element = document.createElement('div');
            element.className = 'floating-element';
            element.style.left = Math.random() * 100 + '%';
            element.style.animationDelay = Math.random() * 2 + 's';
            element.style.animationDuration = (15 + Math.random() * 10) + 's';

            const colors = ['rgba(255, 255, 255, 0.1)', 'rgba(78, 205, 196, 0.1)', 'rgba(255, 107, 107, 0.1)'];
            element.style.background = colors[Math.floor(Math.random() * colors.length)];

            document.querySelector('.floating-elements').appendChild(element);

            setTimeout(() => element.remove(), 25000);
        }

        setInterval(createFloatingElement, 3000);

        // Animación WhatsApp
        lottie.loadAnimation({
            container: document.getElementById('whatsapp-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: "<?php echo e(auto_asset('animaciones/whatsapp.json')); ?>"
        });

        // ========== MODAL DE CONTACTO ==========
        function openContactModal() {
            const modal = document.getElementById('contactModal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeContactModal() {
            const modal = document.getElementById('contactModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function closeContactModalOnOverlay(event) {
            if (event.target === event.currentTarget) {
                closeContactModal();
            }
        }

        // ================= MODAL DE PUBLICACIONES SECUENCIAL =================
        let publicaciones = <?php echo json_encode($publicaciones ?? [], 15, 512) ?>;

        // Invertir array para que se muestre primero la publicación más antigua
        publicaciones = publicaciones.reverse();

        let currentModalIndex = 0;

        function closeCurrentModal() {
            const modal = document.getElementById('publicacionesModal' + currentModalIndex);
            if (modal) {
                modal.classList.remove('show');
            }
            document.body.style.overflow = 'auto';

            // Abrir la siguiente publicación automáticamente
            if (currentModalIndex + 1 < publicaciones.length) {
                currentModalIndex++;
                setTimeout(() => {
                    openCurrentModal();
                }, 300);
            }
        }

        function openCurrentModal() {
            const modal = document.getElementById('publicacionesModal' + currentModalIndex);
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeOnOverlay(event) {
            if (event.target === event.currentTarget) closeCurrentModal();
        }

        // ================= GENERAR MODALES =================
        function generateModalsHTML() {
            const container = document.createElement('div');
            publicaciones.forEach((pub, index) => {
                const modal = document.createElement('div');
                modal.id = 'publicacionesModal' + index;
                modal.className = 'modal-overlay';
                modal.setAttribute('onclick', 'closeOnOverlay(event)');
                modal.innerHTML = `
                <div class="modal-container">
                    <div class="modal-header">
                        <button class="close-btn" onclick="closeCurrentModal()">&times;</button>
            
                    </div>
                    <div class="modal-body" style="max-height:80%; overflow-y:auto;">
                        ${pub.Imagen ? `<img src="<?php echo e(asset('storage')); ?>/${pub.Imagen}" style="width:100%; max-height:500px; object-fit:cover; border-radius:8px;">` : '<p>No hay imagen disponible</p>'}
        
                    </div>
                </div>
            `;
                container.appendChild(modal);
            });
            document.body.appendChild(container);
        }

        generateModalsHTML();

        // ================= AUTO-ABRIR LA PRIMERA =================
        document.addEventListener('DOMContentLoaded', function () {
            if (publicaciones.length > 0) {
                openCurrentModal();
            }

            // Cerrar con ESC
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closeCurrentModal();
            });
        });
    </script>
    <script src="<?php echo e(asset('js/paginaWeb/home.js')); ?>"></script>

</body>

</html><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/paginaWeb/home.blade.php ENDPATH**/ ?>