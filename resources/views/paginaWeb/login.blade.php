<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Login</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <style>
        .forgot-pwd button {
            background-color: transparent;
            color: #007bff;
            border: none;
            padding: 0;
            font-size: 14px;
            text-decoration: underline;
            cursor: pointer;
        }

        .forgot-pwd button:hover {
            color: #0056b3;
        }
    </style>
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
                <!--<div class="small-logo"></div>-->
                <img src="{{ Vite::asset('resources/img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="150px" class="me-2">
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
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.password-toggle');
            if (!passwordInput) return;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '👁‍🗨';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁';
            }
        }

        // Animación de los engranajes
        document.addEventListener('DOMContentLoaded', function() {
            const gears = document.querySelectorAll('.gear');
            gears.forEach((gear, index) => {
                gear.style.animation = `rotate ${2 + index}s linear infinite`;
            });
        });

        // CSS Animation para los engranajes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
