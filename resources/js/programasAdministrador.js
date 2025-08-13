  document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById('nuevoProgramaModal');
        modal.addEventListener('show.bs.modal', function () {
            $.ajax({
                url: "{{ url('administrador/nuevosProgramasAdministrador') }}",
                type: 'GET',
                success: function (data) {
                    $('#nuevoProgramaModal .modal-body').html(data);
                },
                error: function () {
                    $('#nuevoProgramaModal .modal-body').html('<p>Error al cargar el formulario.</p>');
                }
            });
        });
    });