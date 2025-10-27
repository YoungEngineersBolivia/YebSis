<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Login</title>
<<<<<<< HEAD
    <link href="{{ auto_asset('css/login.css') }}" rel="stylesheet">
=======
    <link href="{{ auto_asset('css/paginaWeb/login.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

>>>>>>> a47a3fd99d0f9da3286e5c78198df0f0684bd13a
</head>
<body>
      <a href="{{ route('home') }}" class="btn-home">
            <i class="fas fa-home"></i> Inicio
        </a>



    <div class="container">
        <div class="left-section">
            <div class="grid-pattern"></div>
            <div class="logo-section">
<<<<<<< HEAD
                <img src="{{ Vite::auto_asset('resources/img/ES_logo-grande.png') }}" alt="Logo YE Bolivia" width="500px" class="me-2">
=======
                <img src="{{ auto_asset('img/ES_logo-grande.png') }}" alt="Logo YE Bolivia" width="500px" class="me-2">
>>>>>>> a47a3fd99d0f9da3286e5c78198df0f0684bd13a
            </div>
        </div>
        <div class="right-section">
            <div class="login-header">
                <!--<div class="small-logo"></div>-->
<<<<<<< HEAD
                <img src="{{ Vite::auto_asset('resources/img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="150px" class="me-2">
=======
                <img src="{{ auto_asset('img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="150px" class="me-2">
>>>>>>> a47a3fd99d0f9da3286e5c78198df0f0684bd13a
            </div>
            <p class="welcome-text">
                <b>
                La disciplina de hoy, la libertad del mañana. Inicia sesión para ver el avance de tu pequeño ingeniero
                </b>
            </p>
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="Correo">Correo electrónico</label>
                    <input type="email" name="Correo" id="Correo" class="form-input" placeholder="Ejemplo@gmail.com" required autofocus value="{{ old('Correo') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="Contrasenia">Contraseña:</label>
                    <div class="password-group">
                        <input type="password" name="Contrasenia" id="password" class="form-input" placeholder="Contraseña" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">👁</button>
                    </div>
                    <div class="forgot-pwd">
                        <a href="{{route('password.request')}}">
                            <button type="button">Te olvidaste tu contraseña?</button>
                        </a>
                    </div>
                </div>

                <button type="submit" class="login-button">Iniciar Sesión</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/paginaWeb/login.js') }}"></script>
</body>
</html>
