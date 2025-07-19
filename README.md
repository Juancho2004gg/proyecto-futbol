# Plataforma Web Para La Promoción Y Visibilidad De Futbolistas Venezolanos Ante Patrocinadores, Agentes Y Fanáticos 

El proyecto está centrado en el diseño y prototipo funcional de la plataforma web, priorizando funcionalidades clave como creación de perfiles de futbolistas, búsqueda y visualización de perfiles por seguidores, y gestión de contenido multimedia (videos, fotos, trayectoria).


## Tabla de Contenidos

- [Características](#caracter%C3%ADsticas)
- [Tecnologías Usadas](#tecnolog%C3%ADas-usadas)
- [Requisitos Previos](#requisitos-previos)
- [Guía de Instalación](#gu%C3%ADa-de-instalaci%C3%B3n)
    - [1. Clonar el Repositorio](#1-clonar-el-repositorio)
    - [2. Instalar Dependencias con Docker](#2-instalar-dependencias)
    - [4. Configuración del Entorno](#4-configuraci%C3%B3n-del-entorno)
    - [5. Configuración de la Base de Datos](#5-configuraci%C3%B3n-de-la-base-de-datos)
    - [6. Generar Clave de Aplicación](#6-generar-clave-de-aplicaci%C3%B3n)
    - [7. Compilar Assets](#7-compilar-assets)
    - [8. Ejecutar Migraciones y Seeders (Opcional)](#8-ejecutar-migraciones-y-seeders-opcional)
    - [9. Iniciar el Servidor de Desarrollo](#9-iniciar-el-servidor-de-desarrollo)
- [Uso](#uso)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)

---

## Características

* Enumera aquí las características clave de tu aplicación.
* Ejemplo: Autenticación de usuario (registro, inicio de sesión, cierre de sesión)
* Ejemplo: Creación y visualización de publicaciones
* Ejemplo: Subida de imágenes/archivos adjuntos
* Ejemplo: Perfiles de usuario
* Ejemplo: Funcionalidad de búsqueda

## Tecnologías Usadas

* **Backend:** PHP (Framework Laravel)
* **Frontend:** JavaScript (Vue.js)
* **Herramienta de Compilación:** Vite
* **Gestores de Paquetes:** Composer (para PHP), npm / Yarn (para JavaScript)
* **Base de Datos:** MySQL / PostgreSQL / SQLite (Especifica cuál usas principalmente o soportas)
* **Otros:** (ej. Tailwind CSS, Inertia.js, etc.)

## Requisitos Previos

Antes de comenzar, asegúrate de tener lo siguiente instalado en tu sistema:

* **Git:** Para clonar el repositorio.
    * [Descargar Git](https://git-scm.com/downloads)
* **PHP:** Versión 8.1 o superior (o la versión que requiera tu proyecto Laravel).
    * [Instalar PHP](https://www.php.net/manual/en/install.php)
* **Composer:** Gestor de dependencias de PHP.
    * [Instalar Composer](https://getcomposer.org/download/)
* **Node.js:** Versión 16 o superior (o la versión que requiera tu proyecto).
    * [Descargar Node.js](https://nodejs.org/en/download/) (Incluye npm)
* **npm o Yarn:** Gestor de paquetes JavaScript. npm viene con Node.js. Si prefieres Yarn:
    * [Instalar Yarn](https://classic.yarnpkg.com/lang/en/docs/install/)
* **Servidor de Base de Datos:** (Elige uno según tu `DB_CONNECTION`)
    * [MySQL](https://dev.mysql.com/downloads/mysql/)
    * [PostgreSQL](https://www.postgresql.org/download/)
    * O asegúrate de que SQLite esté habilitado en tu instalación de PHP si usas `sqlite`.
* **Servidor Web:** 
    * Docker para Laravel Sail: [Docker Desktop](https://www.docker.com/products/docker-desktop/)

**Realizar instalacion en WSL:** Todas las instalaciones de PHP, Composer, Node.js y npm/Yarn se deben realizar *dentro* de la distribución WSL (ej. Ubuntu).

## Guía de Instalación

Sigue estos pasos para poner en marcha tu entorno de desarrollo.

### 1. Clonar el Repositorio

Abre tu terminal (ej. terminal de WSL Ubuntu si estás usando WSL) y clona el proyecto:

```bash
git clone [https://github.com/Juancho2004gg/proyecto-futbol.git](https://github.com/Juancho2004gg/proyecto-futbol.git)
cd proyecto-futbol
```
### 2. Instalar dependencias con Docker
```bash
docker run --rm \
    -v "$(pwd)":/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up -d # Levanta los contenedores en segundo plano
./vendor/bin/sail npm install # Instala dependencias de JavaScript
```

### 3. Generar clave de aplicacion
```bash
php artisan key:generate
```

### 4. Compilar assets con Sail
```bash
./vendor/bin/sail npm run dev
```

### 5. Ejecutar migraciones para la base de Datos

```bash
./vendor/bin/sail artisan migrate # Ejecuta las migraciones
./vendor/bin/sail artisan db:seed # Opcional: solo si tienes seeders para datos iniciales
```
