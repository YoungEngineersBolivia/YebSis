<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Login</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="grid-pattern"></div>
            <div class="logo-section">
                <img src="{{ Vite::asset('resources/img/ES_logo-grande.png') }}"alt="Logo YE Bolivia" width="500px" class="me-2">

            </div>
        </div>
        
        <div class="right-section">
            <div class="login-header">
                <!--<div class="small-logo"></div>-->
                    <img src="{{ Vite::asset('resources/img/ES_logo-02.webp') }}"alt="Logo YE Bolivia" width="150px" class="me-2">
            </div>
            
            <p class="welcome-text">
                <b>
                La disciplina de hoy, la libertad del mañana. Inicia sesión para ver el avance de tu pequeño ingeniero
                </b>
            
            </p>
            <form method="POST" action="{{ route('login.submit') }}">
    @csrf

    <div class="form-group">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="Correo" class="form-input" placeholder="Ejemplo@gmail.com" 
               value="{{ old('Correo') }}" required>
    </div>

    <div class="form-group">
        <label class="form-label">Contraseña:</label>
        <div class="password-group">
            <input type="password" name="password" id="password" class="form-input" placeholder="Contraseña" required>

            <button type="button" class="password-toggle" onclick="togglePassword()">👁</button>
        </div>
    </div>

    <button type="submit" class="login-button">Iniciar Sesión</button>
</form>

@if ($errors->any())
    <div style="color:red; margin-top:10px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '👁‍🗨';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁';
            }
        }

        function handleLogin(event) {
            event.preventDefault();
            const email = event.target.querySelector('input[type="email"]').value;
            const password = event.target.querySelector('input[type="password"]').value;
            
            // Aquí puedes agregar la lógica de autenticación
            alert(`Intento de login con:\nEmail: ${email}\nPassword: ${password}`);
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