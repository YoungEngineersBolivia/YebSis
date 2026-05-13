// ========================== HOME.JS ==========================

// Toggle del navbar
function toggleNavbar() {
    var nav = document.getElementById('mainNavLinks');
    nav.classList.toggle('show');
}

// Animaciones y elementos flotantes
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

// ========== MODAL DE PUBLICACIONES ==========
function openPublicacionesModal() {
    const modal = document.getElementById('publicacionesModal');
    if(modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closePublicacionesModal() {
    const modal = document.getElementById('publicacionesModal');
    if(modal) {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

function closePublicacionesModalOnOverlay(event) {
    if (event.target === event.currentTarget) {
        closePublicacionesModal();
    }
}

// ========== DEBUG Y AUTO-ABRIR MODAL ==========
function updateDebugInfo(message, publicacionesCount, hayPublicaciones) {
    const debugElement = document.getElementById('debugText');
    const timestamp = new Date().toLocaleTimeString();
    
    if(debugElement) {
        debugElement.innerHTML = `
            Publicaciones en BD: ${publicacionesCount}<br>
            Variable existe: ${hayPublicaciones ? 'SÍ' : 'NO'}<br>
            Última acción: ${message}<br>
            Hora: ${timestamp}
        `;
    }
}

document.addEventListener('DOMContentLoaded', function() {

    const publicacionesCount = window.publicacionesCount || 0; // Define esta variable en blade
    const hayPublicaciones = publicacionesCount > 0;

    updateDebugInfo("DOM cargado", publicacionesCount, hayPublicaciones);

    if(hayPublicaciones) {
        console.log("✨ Hay publicaciones, abriendo modal automáticamente...");
        setTimeout(() => {
            openPublicacionesModal();
            updateDebugInfo("Modal abierto automáticamente", publicacionesCount, hayPublicaciones);
        }, 1000);
    }
});

// Cerrar modales con ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeContactModal();
        closePublicacionesModal();
    }
});

// Animación de aparición de títulos al hacer scroll
function revealTitlesOnScroll() {
    document.querySelectorAll('.section-title, .program-card').forEach(function(el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 60) {
            el.classList.add('visible');
        }
    });
}
window.addEventListener('scroll', revealTitlesOnScroll);
document.addEventListener('DOMContentLoaded', revealTitlesOnScroll);
