<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación Educativaaaaaaaaaaaaaaaaaaa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f5f5f7;
            color: #333;
            min-height: 100vh;
        }

        .container {
            max-width: 375px;
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #fff;
            border-bottom: 1px solid #e5e5e7;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            width: 28px;
            height: 28px;
            background: linear-gradient(45deg, #4285f4, #ea4335, #fbbc05, #34a853);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .username {
            font-size: 18px;
            font-weight: 500;
            color: #1d1d1f;
        }

        .menu-icon {
            width: 24px;
            height: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            cursor: pointer;
            padding: 2px 0;
        }

        .menu-line {
            width: 100%;
            height: 2px;
            background-color: #333;
            border-radius: 1px;
            transition: all 0.3s ease;
        }

        .menu-icon:hover .menu-line {
            background-color: #007aff;
        }

        .main-content {
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .menu-button {
            width: 100%;
            max-width: 280px;
            background: linear-gradient(135deg, #4a4e69 0%, #3d4158 100%);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 28px 24px;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(74, 78, 105, 0.3);
            position: relative;
            overflow: hidden;
        }

        .menu-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .menu-button:hover::before {
            left: 100%;
        }

        .menu-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(74, 78, 105, 0.4);
        }

        .menu-button:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(74, 78, 105, 0.3);
        }

        .menu-button.evaluate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .menu-button.assigned {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
        }

        .menu-button.registered {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
            line-height: 1.3;
        }

        @media (max-width: 320px) {
            .menu-button {
                font-size: 16px;
                padding: 24px 20px;
            }
            
            .main-content {
                padding: 30px 15px;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-button {
            animation: fadeInUp 0.6s ease forwards;
        }

        .menu-button:nth-child(1) { animation-delay: 0.1s; }
        .menu-button:nth-child(2) { animation-delay: 0.2s; }
        .menu-button:nth-child(3) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <div class="logo-icon">e</div>
                <span class="username">{{ $Usuario->Correo }}</span>
            </div>
            <div class="menu-icon" onclick="toggleMenu()">
                <div class="menu-line"></div>
                <div class="menu-line"></div>
                <div class="menu-line"></div>
            </div>
        </header>

        <main class="main-content">
            <button class="menu-button evaluate" onclick="handleEvaluate()">
                Alumnos
            </button>
            
            <button class="menu-button assigned" onclick="handleAssigned()">
               Inventario
            </button>
        </main>
    </div>

    <script>
        // Funciones para manejar los clics de los botones
        function handleEvaluate() {
            console.log('Evaluar clickeado');
            // Aquí puedes agregar la lógica para la función evaluar
            alert('Función Evaluar seleccionada');
        }

        function handleAssigned() {
            console.log('Alumno Asignado clickeado');
            // Aquí puedes agregar la lógica para alumno asignado
            alert('Alumno Asignado seleccionado');
        }

        function handleRegistered() {
            console.log('Alumno Registrado Clase recuperatoria clickeado');
            // Aquí puedes agregar la lógica para clase recuperatoria
            alert('Alumno Registrado - Clase recuperatoria seleccionado');
        }

        function toggleMenu() {
            console.log('Menú clickeado');
            // Aquí puedes agregar la lógica para mostrar/ocultar menú
            alert('Menú desplegable');
        }

        // Efecto de vibración en móviles (si está disponible)
        function vibrateDevice() {
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        }

        // Agregar vibración a los botones en dispositivos móviles
        document.querySelectorAll('.menu-button').forEach(button => {
            button.addEventListener('click', vibrateDevice);
        });
    </script>
</body>
</html>