@extends('profesor.baseProfesor')

@section('styles')
    <style>
        /* ==== ESTRUCTURA GENERAL ==== */
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            gap: 25px;
            min-height: calc(100vh - 80px);
            background: #fafafa;
            transition: all 0.3s ease;
        }

        /* ==== MENÚ ICONO ==== */
        .menu-icon {
            width: 28px;
            height: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            cursor: pointer;
            padding: 2px 0;
            transition: transform 0.2s ease;
        }

        .menu-line {
            width: 100%;
            height: 3px;
            background-color: #333;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .menu-icon:hover {
            transform: scale(1.1);
        }

        .menu-icon:hover .menu-line {
            background-color: #007aff;
        }

        /* ==== BOTONES ==== */
        .menu-button {
            width: 100%;
            max-width: 320px;
            background: linear-gradient(135deg, #4a4e69 0%, #3d4158 100%);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 24px;
            font-size: 1.1rem;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(74, 78, 105, 0.3);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease forwards;
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

        /* ==== VARIANTES DE COLOR ==== */
        .menu-button.evaluate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            width: 800px;
        }

        .menu-button.assigned {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
        }

        /* ==== ANIMACIONES ==== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==== RESPONSIVIDAD ==== */

        /* 🔹 Celulares (hasta 600px) */
        @media (max-width: 600px) {
            .main-content {
                padding: 25px 15px;
                gap: 20px;
            }

            .menu-button {
                padding: 20px;
                font-size: 1rem;
                border-radius: 16px;
                width: 90%;
            }

            .menu-icon {
                width: 24px;
                height: 24px;
            }
        }

        /* 🔹 Tablets (601px - 1023px) */
        @media (min-width: 601px) and (max-width: 1023px) {
            .main-content {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                gap: 25px;
                padding: 50px 30px;
            }

            .menu-button {
                max-width: 280px;
                font-size: 1.1rem;
                padding: 28px;
                flex: 1 1 45%;
            }
        }

        /* 🔹 Laptops (1024px - 1439px) */
        @media (min-width: 1024px) and (max-width: 1439px) {
            .main-content {
                flex-direction: row;
                justify-content: space-evenly;
                align-items: center;
                gap: 40px;
                padding: 60px 40px;
            }

            .menu-button {
                flex: 1;
                max-width: 350px;
                padding: 30px;
                font-size: 1.2rem;
            }
        }

        /* 🔹 Escritorios grandes (1440px o más) */
        @media (min-width: 1440px) {
            .main-content {
                gap: 60px;
                padding: 80px 60px;
            }

            .menu-button {
                max-width: 400px;
                padding: 35px;
                font-size: 1.3rem;
                border-radius: 25px;
            }
        }
    </style>
@endsection

@section('content')
    <main class="main-content">
       <a href="{{ route('profesor.alumnos') }}">
            <button class="menu-button evaluate">Alumnos</button>
        </a>


        <a href="{{ route()}}"></a>
        <button class="menu-button assigned" onclick="handleAssigned()">
            Inventario
        </button>
    </main>

    <script>
        

        function handleAssigned() {
            alert('Inventario seleccionado');
        }

        function toggleMenu() {
            alert('Menú desplegable');
        }

        function vibrateDevice() {
            if (navigator.vibrate) navigator.vibrate(50);
        }

        document.querySelectorAll('.menu-button').forEach(button => {
            button.addEventListener('click', vibrateDevice);
        });
    </script>
@endsection
