
# 🚀 YebSis - Sistema de Gestión Académica

**YebSis** es una plataforma integral diseñada para la administración y gestión académica de **Young Engineers Bolivia**. Este sistema permite el control eficiente de estudiantes, profesores, tutores, asistencias, inventarios y el área comercial.

---

## 🛠️ Tecnologías y Librerías

Este proyecto está construido con herramientas modernas y robustas:

### Backend
*   **Laravel 12.x** (Framework PHP)
*   **PHP 8.2+**
*   **MySQL** (Base de datos)

### Frontend
*   **Bootstrap 5.3** (Diseño responsivo)
*   **Bootstrap Icons** (Iconografía)
*   **SweetAlert2** (Alertas interactivas y confirmaciones)
*   **Chart.js** (Gráficos estadísticos en Dashboard)

### Librerías Adicionales (Composer)
*   `barryvdh/laravel-dompdf`: Generación de reportes en PDF.
*   `spatie/laravel-permission` (o implementación propia de roles): Gestión de permisos.

---

## 📋 Requisitos Previos

Asegúrate de tener instalado en tu entorno local:
*   [PHP](https://www.php.net/downloads) >= 8.2
*   [Composer](https://getcomposer.org/)
*   [MySQL](https://dev.mysql.com/downloads/installer/) (o MariaDB)
*   [Node.js](https://nodejs.org/) & NPM (Opcional, para compilar assets)

---

## 🚀 Guía de Instalación y Puesta en Marcha

Sigue estos pasos para levantar el proyecto desde cero:

### 1. Clonar el Repositorio
```bash
git clone https://github.com/YoungEngineersBolivia/YebSis.git
cd YebSis
```

### 2. Instalar Dependencias de PHP
```bash
composer install
```

### 3. Configurar Variables de Entorno
Duplica el archivo de ejemplo y configúralo:
```bash
cp .env.example .env
```
Abre el archivo `.env` y configura tu base de datos:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Generar Key de Aplicación
```bash
php artisan key:generate
```

### 5. Migrar la Base de Datos
Crea las tablas en tu base de datos:
```bash
php artisan migrate
```

### 6. Ejecutar Seeders (Datos de Prueba)
Es crucial ejecutar los seeders para tener los roles y el usuario administrador inicial cargados.

### 6. Ejecutar Seeders (Datos de Prueba)
Es crucial ejecutar los seeders para tener los roles, el usuario administrador y los datos base cargados.

**Opción A: Ejecutar todo (Recomendado)**
Este comando ejecutará automáticamente `RolSeeder`, `AdminSeeder` y `RespuestasSeeder` en el orden correcto.
```bash
php artisan db:seed
```

**Opción B: Ejecutar seeders específicos (Manual)**
Si prefieres tener control granular:
```bash
php artisan db:seed --class=RolSeeder
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=RespuestasSeeder
```

### 7. Iniciar el Servidor
```bash
php artisan serve
```
El sistema estará disponible en: [http://localhost:8000](http://localhost:8000)

---

## 📂 Funcionalidades Clave

### 👨‍🏫 Módulo Académico (Profesores)
*   Listado de Alumnos (Asignados y Recuperatorios).
*   **Control de Asistencia:** Marcar "Asistió" o "Falta" con validación de comentarios obligatorios.
*   Registro de observaciones por clase.

### 🏢 Módulo Administrativo
*   **Dashboard:** Métricas, gráficos de ingresos y notificaciones de clases pendientes.
*   **Gestión de Clases de Prueba:** Confirmación de asistencia directa desde el panel.
*   Exportación de reportes de asistencia (PDF y Excel/CSV).
*   Gestión de Usuarios, Roles y Permisos.

### 📈 Módulo Comercial
*   Gestión de Prospectos y Clases de Prueba.
*   Seguimiento de estados (Para inscripción, No asistió, etc.).
*   Edición de clases de prueba y re-agendamiento automático.

---

## 📄 Notas de Desarrollo
*   **Exportación Excel:** Se utiliza una implementación nativa en PHP para generar CSVs ligeros, accesible desde el panel de Asistencia.
*   **Asistencia:** La lógica de asistencia está unificada; los administradores pueden marcar asistencia sin sobrescribir la asignación del profesor original.

---
**Desarrollado para Young Engineers Bolivia 🚀**
