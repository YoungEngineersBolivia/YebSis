  function toggleNavbar() {
            const navLinks = document.getElementById('mainNavLinks');
            navLinks.classList.toggle('show');
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const icon = document.getElementById('dropdownIcon');
            dropdown.classList.toggle('show');
            icon.classList.toggle('open');
        }

        // Cerrar dropdown al hacer clic fuera
        window.onclick = function(event) {
            if (!event.target.matches('.user-button') && !event.target.closest('.user-button')) {
                const dropdown = document.getElementById('dropdownMenu');
                const icon = document.getElementById('dropdownIcon');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    icon.classList.remove('open');
                }
            }
        }

        function logout() {
            if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
                window.location.href = '/logout';
            }
        }

        function openModal(estudianteId, estudianteNombre) {
            document.getElementById('estudiante_id').value = estudianteId;
            document.getElementById('estudiante_nombre').value = estudianteNombre;
            document.getElementById('citaModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('citaModal').classList.remove('show');
            document.getElementById('citaForm').reset();
        }

        function submitCita(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = {
                estudiante_id: formData.get('estudiante_id'),
                fecha: formData.get('fecha'),
                hora: formData.get('hora'),
                motivo: formData.get('motivo')
            };

            console.log('Datos de la cita:', data);
            
            // Aquí iría tu lógica para enviar los datos al servidor
            // Por ahora solo mostramos un mensaje
            alert('Cita agendada exitosamente!\nFecha: ' + data.fecha + '\nHora: ' + data.hora);
            closeModal();
        }

        function verDetalles(estudianteId) {
            console.log('Ver detalles del estudiante:', estudianteId);
            // Aquí puedes redirigir o mostrar más información
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('citaModal').onclick = function(event) {
            if (event.target === this) {
                closeModal();
            }
        }