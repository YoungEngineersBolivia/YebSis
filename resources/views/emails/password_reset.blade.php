<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #007bff;
            margin-top: 0;
        }
        .button {
            display: inline-block;
            padding: 14px 30px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Recuperación de Contraseña</h1>
        </div>
        
        <div class="content">
            <h2>Hola{{ isset($usuario->Nombre) ? ', ' . $usuario->Nombre : '' }}!</h2>
            
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Jóvenes Ingenieros</strong>.</p>
            
            <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">
                    Restablecer Contraseña
                </a>
            </div>
            
            <div class="warning">
                <strong>⚠️ Importante:</strong> Este enlace expirará en <strong>15 minutos</strong> por razones de seguridad.
            </div>
            
            <div class="info-box">
                <p style="margin: 0;"><strong>Si el botón no funciona</strong>, copia y pega el siguiente enlace en tu navegador:</p>
                <p style="margin: 10px 0 0 0; word-break: break-all; font-size: 12px;">
                    <a href="{{ $resetUrl }}" style="color: #007bff;">{{ $resetUrl }}</a>
                </p>
            </div>
            
            <p><strong>Si no solicitaste este cambio de contraseña</strong>, puedes ignorar este correo de forma segura. Tu contraseña actual no será modificada.</p>
            
            <p style="margin-top: 30px;">Saludos,<br><strong>El equipo de Jóvenes Ingenieros</strong></p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>© {{ date('Y') }} Jóvenes Ingenieros. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>