function verTutor(id) {
    // Redirigir a la página de detalles
    window.location.href = `/administrador/tutores/${id}/detalles`;
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch(`/administrador/tutores/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
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
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error al eliminar el tutor");
        });
    }
}

// Manejar el formulario de edición
document.getElementById('formEditarTutor')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert("Error al actualizar tutor");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error al actualizar el tutor");
    });
});