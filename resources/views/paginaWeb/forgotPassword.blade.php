<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Recuperación de Contraseña</title>
    <link href="{{ auto_asset('css/paginaWeb/login.css') }}" rel="stylesheet">
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

        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .captcha-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 2px solid #dee2e6;
        }

        .captcha-question {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .captcha-label {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            margin-bottom: 10px;
        }

        .captcha-input {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }

        .error-text {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="grid-pattern"></div>
            <div class="logo-section">
                <img src="{{ auto_asset('img/ES_logo-grande.png') }}" alt="Logo YE Bolivia" width="500px" class="me-2">
            </div>
        </div>
        <div class="right-section">
            <div class="login-header">
                <img src="{{ auto_asset('img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="150px" class="me-2">
            </div>
            <p class="welcome-text">
                <b>
                Recupera tu contraseña para continuar con tu sesión y seguir avanzando.
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
            
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="Correo">Correo electrónico</label>
                    <input 
                        type="email" 
                        name="Correo" 
                        id="Correo" 
                        class="form-input" 
                        placeholder="Ejemplo@gmail.com" 
                        required 
                        autofocus 
                        value="{{ old('Correo') }}"
                    >
                    @error('Correo')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="captcha-container">
                    <div class="captcha-label">🔒 Verificación de seguridad</div>
                    <div class="captcha-question">
                        {{ session('captcha_question', $captcha_question ?? '? + ?') }} = ?
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="captcha">¿Cuál es el resultado?</label>
                        <input 
                            type="number" 
                            name="captcha" 
                            id="captcha" 
                            class="form-input captcha-input" 
                            placeholder="Tu respuesta" 
                            required
                            autocomplete="off"
                        >
                        @error('captcha')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="login-button">Recuperar Contraseña</button>
            </form>
            
            <div class="back-to-login">
                <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
</body>
</html>