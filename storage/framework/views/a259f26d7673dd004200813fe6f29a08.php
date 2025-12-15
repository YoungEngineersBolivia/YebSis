<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Credenciales Administrador</title>
</head>
<body>
    <h2>Hola <?php echo e($nombre); ?></h2>
    <p>Su cuenta para Young Engineers ha sido creada correctamente.</p>

    <p><strong>Correo:</strong> <?php echo e($correo); ?></p>
    <p><strong>Contraseña temporal:</strong> <?php echo e($clave); ?></p>

    <p>Por razones de seguridad, cambie su contraseña después de iniciar sesión.</p>
    <p>Gracias,<br>Equipo YE Bolivia</p>
</body>
</html>
<?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/emails/claveGenerada.blade.php ENDPATH**/ ?>