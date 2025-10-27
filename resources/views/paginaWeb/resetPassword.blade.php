<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Restablecer Contraseña</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="grid-pattern"></div>
            <div class="logo-section">
                <img src="{{ Vite::asset('resources/img/ES_logo-grande.png') }}" alt="Logo YE Bolivia" width="500px" class="me-2">
            </div>
        </div>
        <div class="right-section">
            <div class="login-header">
                <img src="{{ Vite::asset('resources/img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="150px" class="me-2">
            </div>
            <p class="welcome-text">
                <b>Ingresa tu nueva contraseña</b>
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <label class="form-label" for="Correo">Correo electrónico</label>
                    <input type="email" name="Correo" id="Correo" class="form-input" placeholder="Ejemplo@gmail.com" required autofocus value="{{ old('Correo', request()->Correo) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Nueva Contraseña</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Ingresa tu nueva contraseña" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Confirma tu nueva contraseña" required>
                </div>

                <button type="submit" class="login-button">Restablecer Contraseña</button>
            </form>

            <div class="back-to-login">
                <a href="{{ route('login') }}">Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
</body>
</html>