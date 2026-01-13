<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Restablecer Contraseña</title>
    <link href="{{ asset('css/paginaWeb/login.css') }}" rel="stylesheet">
    <style>
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
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

        .captcha-image-wrapper {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }

        .captcha-image {
            border: 2px solid #ced4da;
            border-radius: 5px;
            background-color: #fff;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 100%;
            height: auto;
        }

        .captcha-label {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            margin-bottom: 10px;
        }

        .captcha-refresh {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .captcha-refresh:hover {
            background-color: #0056b3;
        }

        .captcha-refresh:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .captcha-input {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .captcha-instructions {
            font-size: 13px;
            color: #495057;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .error-text {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
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

        .password-requirements {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
            padding-left: 15px;
        }

        .password-requirements li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="grid-pattern"></div>
            <div class="logo-section">
                <img src="{{ asset('img/ES_logo-grande.png') }}" alt="Logo YE Bolivia" width="500px" class="me-2">
            </div>
        </div>
        <div class="right-section">
            <div class="login-header">
                <img src="{{ asset('img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="150px" class="me-2">
            </div>
            <p class="welcome-text">
                <b>Ingresa tu nueva contraseña</b>
            </p>

            @if ($errors->has('token'))
                <div class="alert alert-danger">
                    {{ $errors->first('token') }}
                </div>
            @endif
            
            @if ($errors->has('Correo'))
                <div class="alert alert-danger">
                    {{ $errors->first('Correo') }}
                </div>
            @endif
            
            @if ($errors->has('password'))
                <div class="alert alert-danger">
                    {{ $errors->first('password') }}
                </div>
            @endif
            
            @if ($errors->has('captcha'))
                <div class="alert alert-danger">
                    {{ $errors->first('captcha') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" id="reset-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <label class="form-label" for="Correo">Correo electrónico</label>
                    <input type="email" name="Correo" id="Correo" class="form-input" 
                           placeholder="Ejemplo@gmail.com" required autofocus 
                           value="{{ old('Correo', $Correo ?? '') }}" readonly>
                    @error('Correo')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Nueva Contraseña</label>
                    <input type="password" name="password" id="password" class="form-input" 
                           placeholder="Ingresa tu nueva contraseña" required>
                    <ul class="password-requirements">
                        <li>Mínimo 8 caracteres</li>
                        <li>Se recomienda incluir mayúsculas, minúsculas y números</li>
                    </ul>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="form-input" placeholder="Confirma tu nueva contraseña" required>
                </div>

                <div class="captcha-container">
                    <div class="captcha-label">🔒 Verificación de seguridad</div>
                    <div class="captcha-instructions">
                        Ingresa los caracteres que ves en la imagen
                    </div>
                    
                    <div class="captcha-image-wrapper">
                        <img src="{{ route('captcha.generate') }}?{{ time() }}" 
                             alt="CAPTCHA" 
                             class="captcha-image"
                             id="captcha-img">
                    </div>
                    
                    <div style="text-align: center;">
                        <button type="button" class="captcha-refresh" onclick="refreshCaptcha()" id="refresh-btn">
                            🔄 Generar nuevo código
                        </button>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0; margin-top: 15px;">
                        <label class="form-label" for="captcha">Ingresa el código</label>
                        <input 
                            type="text" 
                            name="captcha" 
                            id="captcha" 
                            class="form-input captcha-input" 
                            placeholder="Escribe el código aquí" 
                            required
                            autocomplete="off"
                            maxlength="6"
                        >
                        @error('captcha')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="login-button">Restablecer Contraseña</button>
            </form>

            <div class="back-to-login">
                <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
            </div>
        </div>
    </div>

    <script>
        function refreshCaptcha() {
            const captchaImg = document.getElementById('captcha-img');
            const refreshBtn = document.getElementById('refresh-btn');
            const captchaInput = document.getElementById('captcha');
            
            // Deshabilitar el botón temporalmente
            refreshBtn.disabled = true;
            refreshBtn.textContent = '⏳ Cargando...';
            
            // Actualizar imagen con timestamp para evitar cache
            captchaImg.src = '{{ route("captcha.generate") }}?' + new Date().getTime();
            
            // Limpiar input
            captchaInput.value = '';
            
            // Llamar al backend para regenerar el código en sesión
            fetch('{{ route("captcha.refresh") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Rehabilitar el botón
                setTimeout(() => {
                    refreshBtn.disabled = false;
                    refreshBtn.textContent = '🔄 Generar nuevo código';
                    captchaInput.focus();
                }, 500);
            })
            .catch(error => {
                console.error('Error al refrescar CAPTCHA:', error);
                refreshBtn.disabled = false;
                refreshBtn.textContent = '🔄 Generar nuevo código';
            });
        }

        // Convertir input a mayúsculas automáticamente
        document.getElementById('captcha').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        // Validación de coincidencia de contraseñas
        document.getElementById('reset-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            if (password !== confirmation) {
                e.preventDefault();
                alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
                document.getElementById('password_confirmation').focus();
            }
        });
    </script>
</body>
</html>