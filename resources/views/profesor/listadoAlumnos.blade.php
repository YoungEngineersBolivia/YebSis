@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profesor/listadoAlumnos.css') }}">
@endsection

@section('content')
<div class="search-container">
    <input 
        type="text" 
        class="search-box" 
        placeholder="Buscar Estudiantes"
        id="searchInput"
    >
</div>

<div class="students-list" id="studentsList">
    @if(isset($estudiantes) && $estudiantes->count() > 0)
        @foreach($estudiantes as $estudiante)
        <div 
            class="student-item" 
            onclick="window.location.href='{{ route('profesor.detalle-estudiante', $estudiante->Id_estudiantes) }}'"
        >
            {{-- Avatar según género --}}
            <img 
                src="{{ asset('images/' . ($estudiante->persona?->Genero === 'M' ? 'avatar-boy.png' : 'avatar-girl.png')) }}" 
                alt="{{ $estudiante->persona?->Nombre ?? 'Estudiante' }}" 
                class="student-avatar"
            >

            {{-- Información del estudiante --}}
            <div class="student-info">
                <p class="student-name">
                    {{ $estudiante->persona?->Nombre }} {{ $estudiante->persona?->Apellido }}
                </p>
                <p class="student-program">
                    {{ $estudiante->programa?->Nombre_programa ?? 'Sin programa' }}
                </p>
            </div>

            {{-- Horario (si tienes relación con la tabla horarios) --}}
            <span class="student-time">
                @if($estudiante->horario)
                    {{ $estudiante->horario->Dia_clase_uno ?? '' }} 
                    {{ $estudiante->horario->Horario_clase_uno ?? '' }}
                @else
                    —
                @endif
            </span>
        </div>
        @endforeach
    @else
        <div class="no-students">
            <p>No hay estudiantes disponibles en esta categoría</p>
        </div>
    @endif
</div>

{{-- Buscador dinámico --}}
<script>
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const studentItems = document.querySelectorAll('.student-item');
    
    studentItems.forEach(item => {
        const name = item.querySelector('.student-name').textContent.toLowerCase();
        const program = item.querySelector('.student-program').textContent.toLowerCase();
        
        item.style.display = (name.includes(searchTerm) || program.includes(searchTerm)) ? 'flex' : 'none';
    });
});
</script>
@endsection
