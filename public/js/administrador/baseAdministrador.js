   // Mantener el dropdown abierto si está activo
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const links = document.querySelectorAll('.nav-link');
            
            links.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    
                    // Si está dentro de un collapse, abrirlo
                    const collapse = link.closest('.collapse');
                    if (collapse) {
                        collapse.classList.add('show');
                    }
                }
            });
        });

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);