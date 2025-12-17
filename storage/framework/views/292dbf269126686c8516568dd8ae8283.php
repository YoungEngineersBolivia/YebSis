<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Recuperación de Contraseña</title>
    <link href="<?php echo e(auto_asset('css/paginaWeb/login.css')); ?>" rel="stylesheet">
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
                <img src="<?php echo e(auto_asset('img/ES_logo-grande.png')); ?>" alt="Logo YE Bolivia" width="500px" class="me-2">
            </div>
        </div>
        <div class="right-section">
            <div class="login-header">
                <img src="<?php echo e(auto_asset('img/ES_logo-02.webp')); ?>" alt="Logo YE Bolivia" width="150px" class="me-2">
            </div>
            <p class="welcome-text">
                <b>
                Recupera tu contraseña para continuar con tu sesión y seguir avanzando.
                </b>
            </p>
            <?php if(session('status')): ?>
                <div class="alert alert-success"><?php echo e(session('status')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label class="form-label" for="Correo">Correo electrónico</label>
                    <input type="email" name="Correo" id="Correo" class="form-input" placeholder="Ejemplo@gmail.com" required autofocus value="<?php echo e(old('Correo')); ?>">
                </div>

                <button type="submit" class="login-button">Recuperar Contraseña</button>
            </form>
            <div class="back-to-login">
                <a href="<?php echo e(route('login')); ?>">Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/paginaWeb/forgotPassword.blade.php ENDPATH**/ ?>