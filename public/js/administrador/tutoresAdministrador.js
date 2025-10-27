  function verTutor(id) {
        fetch(`/administrador/tutores/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('verNombre').textContent = data.persona.Nombre;
                document.getElementById('verApellido').textContent = data.persona.Apellido;
                document.getElementById('verCelular').textContent = data.persona.Celular;
                document.getElementById('verDireccion').textContent = data.persona.Direccion_domicilio;
                document.getElementById('verCorreo').textContent = data.usuario.Correo;
                document.getElementById('verParentesco').textContent = data.Parentesco;
                document.getElementById('verDescuento').textContent = data.Descuento;
                document.getElementById('verNit').textContent = data.Nit;

                const modal = new bootstrap.Modal(document.getElementById('modalVerTutor'));
                modal.show();
            });
    }

    function editarTutor(id) {
        fetch(`/administrador/tutores/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editarNombre').value = data.persona.Nombre;
                document.getElementById('editarApellido').value = data.persona.Apellido;
                document.getElementById('editarCelular').value = data.persona.Celular;
                document.getElementById('editarDireccion').value = data.persona.Direccion_domicilio;
                document.getElementById('editarCorreo').value = data.usuario.Correo;
                document.getElementById('editarParentesco').value = data.Parentesco;
                document.getElementById('editarDescuento').value = data.Descuento;
                document.getElementById('editarNit').value = data.Nit;

                const form = document.getElementById('formEditarTutor');
                form.action = `/administrador/tutores/${id}`;
                const modal = new bootstrap.Modal(document.getElementById('modalEditarTutor'));
                modal.show();
            });
    }

    function eliminarTutor(id) {
        if (confirm("¿Estás seguro de eliminar este tutor?")) {
            fetch(`/administrador/tutores/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Error al eliminar tutor: " + (data.message || 'No se pudo eliminar.'));
                }
            });
        }
    }