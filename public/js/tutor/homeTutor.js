// Obtener el token CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Toggle del menú de navegación
function toggleNavbar() {
    const navLinks = document.getElementById('mainNavLinks');
    navLinks.classList.toggle('active');
}

// Toggle del dropdown de usuario
function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    const icon = document.getElementById('dropdownIcon');
    dropdown.classList.toggle('show');
    icon.classList.toggle('rotate');
}

// Cerrar dropdown al hacer clic fuera
window.onclick = function(event) {
    if (!event.target.matches('.user-button') && !event.target.matches('.user-button *')) {
        const dropdown = document.getElementById('dropdownMenu');
        const icon = document.getElementById('dropdownIcon');
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
            icon.classList.remove('rotate');
        }
    }
}

// Función para cerrar sesión
async function logout() {
    if (confirm('¿Está seguro que desea cerrar sesión?')) {
        try {
            const response = await fetch('/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (response.ok) {
                window.location.href = '/login';
            } else {
                alert('Error al cerrar sesión');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cerrar sesión');
        }
    }
}

// Función para ver detalles del estudiante
async function verDetalles(estudianteId) {
    try {
        const response = await fetch(`/tutor/estudiante/${estudianteId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();

        if (data.success) {
            mostrarDetallesEstudiante(data.estudiante);
        } else {
            alert('Error al cargar los detalles del estudiante');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar los detalles');
    }
}

// Función para mostrar detalles en un modal (puedes personalizar esto)
function mostrarDetallesEstudiante(estudiante) {
    const detalles = `
        Nombre: ${estudiante.persona.Nombre} ${estudiante.persona.Apellido}
        Código: ${estudiante.Cod_estudiante}
        Programa: ${estudiante.programa?.Nombre || 'No asignado'}
        Estado: ${estudiante.Estado}
        Fecha de nacimiento: ${estudiante.persona.Fecha_nacimiento || 'No registrada'}
        Celular: ${estudiante.persona.Celular || 'No registrado'}
        Dirección: ${estudiante.persona.Direccion_domicilio || 'No registrada'}
    `;
    
    alert(detalles);
    // Puedes crear un modal más elegante aquí
}

// Abrir modal de agendar cita
function openModal(estudianteId, nombreEstudiante) {
    document.getElementById('estudiante_id').value = estudianteId;
    document.getElementById('estudiante_nombre').value = nombreEstudiante;
    document.getElementById('citaModal').style.display = 'flex';
    
    // Establecer fecha mínima como hoy
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_cita').setAttribute('min', hoy);
}

// Cerrar modal
function closeModal() {
    document.getElementById('citaModal').style.display = 'none';
    document.getElementById('citaForm').reset();
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('citaModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Submit del formulario de cita
async function submitCita(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitButton = form.querySelector('.btn-submit');
    const originalText = submitButton.textContent;
    
    // Deshabilitar botón
    submitButton.disabled = true;
    submitButton.textContent = 'Agendando...';
    
    const formData = {
        estudiante_id: document.getElementById('estudiante_id').value,
        fecha: document.getElementById('fecha_cita').value,
        hora: document.getElementById('hora_cita').value,
        motivo: document.getElementById('motivo_cita').value
    };
    
    try {
        const response = await fetch('/tutor/agendar-cita', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('¡Cita agendada exitosamente!');
            closeModal();
            // Opcional: Recargar la página o actualizar la lista
            // location.reload();
        } else {
            alert(data.error || 'Error al agendar la cita');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    } finally {
        // Rehabilitar botón
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
}

// Función para ver evaluaciones
async function verEvaluaciones(estudianteId) {
    try {
        const response = await fetch(`/tutor/evaluaciones/${estudianteId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();

        if (data.success) {
            mostrarEvaluaciones(data.evaluaciones, data.estudiante);
        } else {
            alert('Error al cargar las evaluaciones');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar las evaluaciones');
    }
}

// Función para mostrar evaluaciones (personaliza según necesites)
function mostrarEvaluaciones(evaluaciones, estudiante) {
    if (evaluaciones.length === 0) {
        alert('Este estudiante aún no tiene evaluaciones registradas');
        return;
    }
    
    // Aquí puedes crear un modal más elegante
    let mensaje = `Evaluaciones de ${estudiante.persona.Nombre}:\n\n`;
    evaluaciones.forEach((ev, index) => {
        mensaje += `${index + 1}. Fecha: ${ev.fecha_evaluacion}\n`;
        mensaje += `   Modelo: ${ev.Nombre_modelo}\n`;
        mensaje += `   Profesor: ${ev.profesor_nombre} ${ev.profesor_apellido}\n\n`;
    });
    
    alert(mensaje);
}

// Inicialización cuando carga la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Panel del tutor cargado correctamente');
    
    // Aquí puedes agregar más inicializaciones si es necesario
});