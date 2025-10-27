 function editarUsuario(id) {
        fetch(`/admin/usuarios/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editarNombre').value = data.Nombre;
                document.getElementById('editarApellido').value = data.Apellido;
                document.getElementById('editarCorreo').value = data.Correo;
                document.getElementById('editarRol').value = data.Rol;

                const form = document.getElementById('formEditarUsuario');
                form.action = `/admin/usuarios/${id}`;

                const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
                modal.show();
            });
    }

    function eliminarUsuario(id) {
        if (confirm("¿Estás seguro de eliminar este usuario?")) {
            fetch(`/admin/usuarios/${id}`, {
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
                    alert("Error al eliminar usuario: " + data.message);
                }
            });
        }
    }