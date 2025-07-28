<form method="POST" action="{{ route('programas.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="Nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="Nombre" name="Nombre" required>
    </div>
    <!-- Más campos aquí -->
    <button type="submit" class="btn btn-success">Guardar</button>
</form>
