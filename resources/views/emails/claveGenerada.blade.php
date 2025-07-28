<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Credenciales Administrador</title>
</head>
<body>
    <h2>Hola {{ $nombre }}</h2>
    <p>Su cuenta como administrador ha sido creada correctamente.</p>

    <p><strong>Correo:</strong> {{ $correo }}</p>
    <p><strong>Contraseña temporal:</strong> {{ $clave }}</p>

    <p>Por razones de seguridad, cambie su contraseña después de iniciar sesión.</p>
    <p>Gracias,<br>Equipo YE Bolivia</p>
</body>
</html>
