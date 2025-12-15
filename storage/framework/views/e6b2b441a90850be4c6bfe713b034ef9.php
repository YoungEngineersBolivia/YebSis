<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jóvenes Ingenieros - Login</title>
    <link href="<?php echo e(auto_asset('css/paginaWeb/login.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
      <a href="<?php echo e(route('home')); ?>" class="btn-home">
            <i class="fas fa-home"></i> Inicio
        </a>



    <div class="container">
        <div class="left-section">
            <div class="grid-pattern"></div>
            <div class="logo-section">
                <img src="<?php echo e(auto_asset('img/ES_logo-grande.png')); ?>" alt="Logo YE Bolivia" width="500px" class="me-2">
            </div>
        </div>
        <div class="right-section">
            <div class="login-header">
                <!--<div class="small-logo"></div>-->
                <img src="<?php echo e(auto_asset('img/ES_logo-02.webp')); ?>" alt="Logo YE Bolivia" width="150px" class="me-2">
            </div>
            <p class="welcome-text">
                <b>
                La disciplina de hoy, la libertad del mañana. Inicia sesión para ver el avance de tu pequeño ingeniero
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
            <form method="POST" action="<?php echo e(route('login.submit')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label class="form-label" for="Correo">Correo electrónico</label>
                    <input type="email" name="Correo" id="Correo" class="form-input" placeholder="Ejemplo@gmail.com" required autofocus value="<?php echo e(old('Correo')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="Contrasenia">Contraseña:</label>
                    <div class="password-group">
                        <input type="password" name="Contrasenia" id="password" class="form-input" placeholder="Contraseña" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">👁</button>
                    </div>
                    <div class="forgot-pwd">
                        <a href="<?php echo e(route('password.request')); ?>">
                            <button type="button">Te olvidaste tu contraseña?</button>
                        </a>
                    </div>
                </div>

                <button type="submit" class="login-button">Iniciar Sesión</button>
            </form>
        </div>
    </div>
    <script src="<?php echo e(asset('js/paginaWeb/login.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/paginaWeb/login.blade.php ENDPATH**/ ?>