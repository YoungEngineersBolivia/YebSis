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
                    <input 
                        type="email" 
                        name="Correo" 
                        id="Correo" 
                        class="form-input" 
                        placeholder="Ejemplo@gmail.com" 
                        required 
                        autofocus 
                        value="<?php echo e(old('Correo')); ?>"
                    >
                    <?php $__errorArgs = ['Correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-text"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="captcha-container">
                    <div class="captcha-label">🔒 Verificación de seguridad</div>
                    <div class="captcha-question">
                        <?php echo e(session('captcha_question', $captcha_question ?? '? + ?')); ?> = ?
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
                        <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-text"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <button type="submit" class="login-button">Recuperar Contraseña</button>
            </form>
            
            <div class="back-to-login">
                <a href="<?php echo e(route('login')); ?>">← Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/paginaWeb/forgotPassword.blade.php ENDPATH**/ ?>