@extends('/administrador/baseAdministrador') {{-- Indica que esta vista extiende el layout 'layouts/app.blade.php' --}}

@section('title', 'Página de Inicio') {{-- Define el contenido para la sección 'title' --}}

@section('head_extra')
    {{-- Puedes añadir CSS específico para esta página aquí --}}
    <style>
        .container {
            padding: 20px;
            background-color: #ff0000ff;
        }
    </style>
@endsection

@section('content') {{-- Define el contenido para la sección principal 'content' --}}
    <h1>¡Bienvenido a mi aplicación!</h1>
    <p>Este es el contenido específico de la página de inicio. La cabecera, navegación y pie de página provienen del layout maestro.</p>
    <button onclick="alert('Hola desde la página de inicio!')">Haz clic aquí</button>
@endsection

@section('scripts_extra')
    {{-- Puedes añadir JS específico para esta página aquí --}}
    <script>
        console.log('Script específico de la página de inicio cargado.');
    </script>
@endsection
