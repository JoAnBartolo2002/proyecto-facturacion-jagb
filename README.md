📝 Facturación CRUD - JAGB

Sistema básico de gestión de facturas que integra autenticación de usuarios y registro. La aplicación permite el ingreso de datos para generar facturas mediante validaciones, asegurando la integridad de la información en todo momento.

🚀 Características Principales

Gestión Integral (CRUD): Creación, lectura, actualización y eliminación de facturas.
Seguridad: Sistema de Login y Registro de usuarios.
Privacidad de Datos: Arquitectura relacional ($1:N$); cada usuario gestiona exclusivamente sus propias facturas, garantizando la privacidad y la correcta asociación de los datos.
Validaciones: Control estricto de captura de información tanto en el acceso como en los formularios de facturación.

🛠️ Stack
Backend: Laravel (PHP)
Frontend: Vue.js
Estilos: Tailwind CSS
Contenerización: Docker (Laravel Sail)

📦 Instrucciones para el Despliegue
Siga estos pasos para clonar y levantar el entorno de desarrollo de forma local utilizando Docker.

## 1️⃣ Preparación del Código

```powershell
git clone https://github.com/JoAnBartolo2002/proyecto-facturacion-jagb
cd proyecto-facturacion-jagb
copy .env.example .env
composer install
```

---

## 2️⃣ Construcción del Entorno Docker

```powershell
docker-compose up -d --build
```

---

## 3️⃣ Instalación de Dependencias y Compilación de Assets

```powershell
docker exec -it proyecto-facturacion-jagb-laravel.test-1 npm install
docker exec -it proyecto-facturacion-jagb-laravel.test-1 npm run build
```

---

## 4️⃣ Configuración de la Aplicación y Base de Datos

```powershell
docker exec -it proyecto-facturacion-jagb-laravel.test-1 php artisan key:generate
docker exec -it proyecto-facturacion-jagb-laravel.test-1 php artisan migrate
```

---

## 5️⃣ Acceder a la Aplicación

Abrir en el navegador:

```
http://localhost/
```
