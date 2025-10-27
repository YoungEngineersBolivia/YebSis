function abrirModalClasePrueba(id, nombre) {
    // Usar Bootstrap 5 modal
    const modal = new bootstrap.Modal(document.getElementById('modalClasePrueba'));
    modal.show();
    
    document.getElementById('modalIdProspectos').value = id;
    document.getElementById('modalNombreProspecto').value = nombre;
    document.getElementById('modalNombreEstudiante').value = nombre;
    document.querySelector('textarea[name="Comentarios"]').value = 'Clase de prueba asignada a: ' + nombre;
}

// Función para ver detalles de la clase de prueba
function verClasePrueba(nombre, fecha, hora, comentarios) {
    document.getElementById('verNombreEstudiante').textContent = nombre;
    document.getElementById('verFechaClase').textContent = fecha;
    document.getElementById('verHoraClase').textContent = hora;
    document.getElementById('verComentariosClase').textContent = comentarios;
    const modal = new bootstrap.Modal(document.getElementById('modalVerClasePrueba'));
    modal.show();
}

// Función para actualizar automáticamente el estado cuando se asigna una clase
function actualizarEstadoAClaseAsignada(prospectoId) {
    fetch(`/prospectos/${prospectoId}/updateEstado`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            'Estado_prospecto': 'clase asignada'
        })
    });
}

// Cambiar rango de fechas
function cambiarRango(direccion) {
    const inputDesde = document.getElementById('inputDesde');
    const inputHasta = document.getElementById('inputHasta');
    let desde = inputDesde.value ? new Date(inputDesde.value) : null;
    let hasta = inputHasta.value ? new Date(inputHasta.value) : null;

    // Si no hay fechas, usar hoy y hoy+6 días
    if (!desde || !hasta) {
        const hoy = new Date();
        desde = new Date(hoy);
        hasta = new Date(hoy);
        hasta.setDate(hasta.getDate() + 6);
    }

    // Calcular la diferencia de días
    const diff = Math.round((hasta - desde) / (1000*60*60*24)) || 6;
    let nuevoDesde, nuevoHasta;
    if (direccion === 'prev') {
        nuevoDesde = new Date(desde);
        nuevoDesde.setDate(nuevoDesde.getDate() - (diff+1));
        nuevoHasta = new Date(hasta);
        nuevoHasta.setDate(nuevoHasta.getDate() - (diff+1));
    } else {
        nuevoDesde = new Date(desde);
        nuevoDesde.setDate(nuevoDesde.getDate() + (diff+1));
        nuevoHasta = new Date(hasta);
        nuevoHasta.setDate(nuevoHasta.getDate() + (diff+1));
    }
    // Formatear YYYY-MM-DD
    function formatDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
    }
    inputDesde.value = formatDate(nuevoDesde);
    inputHasta.value = formatDate(nuevoHasta);
    document.getElementById('filtroFechasForm').submit();
}

// Aplicar colores según el estado al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.estado-select');
    selects.forEach(function(select) {
        const estado = select.value.replace(/\s+/g, '.');
        select.classList.remove('estado-nuevo', 'estado-contactado', 'estado-clase.de.prueba');
        select.classList.add('estado-' + estado);
        
        // Cambiar colores al seleccionar
        select.addEventListener('change', function() {
            const nuevoEstado = this.value.replace(/\s+/g, '.');
            this.classList.remove('estado-nuevo', 'estado-contactado', 'estado-clase.de.prueba');
            this.classList.add('estado-' + nuevoEstado);
        });
    });
});