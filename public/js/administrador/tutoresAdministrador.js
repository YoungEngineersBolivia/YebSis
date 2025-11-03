(function () {
    document.addEventListener('DOMContentLoaded', function() {

        // Ver tutor
        document.querySelectorAll('.btn-ver-tutor').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                fetch("{{ route('tutores.show', '') }}/" + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('verNombre').textContent = data.persona?.Nombre || 'N/A';
                    document.getElementById('verApellido').textContent = data.persona?.Apellido || 'N/A';
                    document.getElementById('verCelular').textContent = data.persona?.Celular || 'N/A';
                    document.getElementById('verDireccion').textContent = data.persona?.Direccion_domicilio || 'N/A';
                    document.getElementById('verCorreo').textContent = data.usuario?.Correo || 'N/A';
                    document.getElementById('verParentesco').textContent = data.Parentesco || 'N/A';
                    document.getElementById('verDescuento').textContent = data.Descuento || '0';
                    document.getElementById('verNit').textContent = data.Nit || 'N/A';

                    const modal = new bootstrap.Modal(document.getElementById('modalVerTutor'));
                    modal.show();
                })
                .catch(error => console.error('Error al cargar los datos del tutor:', error));
            });
        });

        // Editar tutor
        document.querySelectorAll('.btn-editar-tutor').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                fetch("{{ route('tutores.edit', '') }}/" + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('editarNombre').value = data.persona?.Nombre || '';
                    document.getElementById('editarApellido').value = data.persona?.Apellido || '';
                    document.getElementById('editarCelular').value = data.persona?.Celular || '';
                    document.getElementById('editarDireccion').value = data.persona?.Direccion_domicilio || '';
                    document.getElementById('editarCorreo').value = data.usuario?.Correo || '';
                    document.getElementById('editarParentesco').value = data.Parentesco || '';
                    document.getElementById('editarDescuento').value = data.Descuento || '';
                    document.getElementById('editarNit').value = data.Nit || '';

                    const form = document.getElementById('formEditarTutor');
                    form.action = "{{ route('tutores.update', '') }}/" + id;

                    const modal = new bootstrap.Modal(document.getElementById('modalEditarTutor'));
                    modal.show();
                })
                .catch(error => console.error('Error al cargar los datos del tutor:', error));
            });
        });

        // Cerrar alertas automáticamente después de 5 segundos
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 5000);
        });
    });
})();
