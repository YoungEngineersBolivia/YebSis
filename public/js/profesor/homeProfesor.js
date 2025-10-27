
        function handleAssigned() {
            alert('Inventario seleccionado');
        }

        function toggleMenu() {
            alert('Menú desplegable');
        }

        function vibrateDevice() {
            if (navigator.vibrate) navigator.vibrate(50);
        }

        document.querySelectorAll('.menu-button').forEach(button => {
            button.addEventListener('click', vibrateDevice);
        });