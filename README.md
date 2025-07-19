# Plataforma Web Para La Promoción Y Visibilidad De Futbolistas Venezolanos Ante Patrocinadores, Agentes Y Fanáticos 

El proyecto está centrado en el diseño y prototipo funcional de la plataforma web, priorizando funcionalidades clave como creación de perfiles de futbolistas, búsqueda y visualización de perfiles por seguidores, y gestión de contenido multimedia (videos, fotos, trayectoria).


## Tabla de Contenidos

- [Tecnologías Usadas](#tecnolog%C3%ADas-usadas)
- [Requisitos Previos](#requisitos-previos)
- [Guía de Instalación](#gu%C3%ADa-de-instalaci%C3%B3n)
    - [1. Clonar el Repositorio](#1-clonar-el-repositorio)
    - [2. Instalar Dependencias con Docker](#2-instalar-dependencias)
    - [3. Generar Clave de Aplicación](#3-generar-clave-de-aplicacion)
    - [4. Compilar Assets](#4-compilar-assets)
    - [5. Ejecutar Migraciones y Seeders](#5-ejecutar-migraciones)
    - [6. Iniciar el Servidor de Desarrollo](#6-iniciar-el-servidor-de-desarrollo)
- [Comandos](#comandos)

---
## Tecnologías Usadas

* **Backend:** PHP (Framework Laravel)
* **Frontend:** JavaScript (Vue.js)
* **Herramienta de Compilación:** Vite
* **Gestores de Paquetes:** Composer (para PHP), npm (para JavaScript)
* **Base de Datos:** MySQL
* **Otros:** (Tailwind CSS, Inertia.js, etc.)

## Requisitos Previos

* **Git:** Para clonar el repositorio.
    * [Descargar Git](https://git-scm.com/downloads)
* **PHP:** Versión 8.1 o superior
    * [Instalar PHP](https://www.php.net/manual/en/install.php)
* **Composer:** Gestor de dependencias de PHP.
    * [Instalar Composer](https://getcomposer.org/download/)
* **Node.js:** Versión 16 o superior (o la versión que requiera tu proyecto).
    * [Descargar Node.js](https://nodejs.org/en/download/) (Incluye npm)
* **npm o Yarn:** Gestor de paquetes JavaScript. npm viene con Node.js.
* **Servidor de Base de Datos:**  [MySQL](https://dev.mysql.com/downloads/mysql/)
* **Servidor Web:** Docker para Laravel Sail: [Docker Desktop](https://www.docker.com/products/docker-desktop/)

**Realizar instalacion en WSL:** Todas las instalaciones de PHP, Composer, Node.js y npm se deben realizar *dentro* de la distribución WSL (ej. Ubuntu).

## Guía de Instalación

### 1. Clonar el Repositorio

Abrir terminal de WSL Ubuntu y clonar el proyecto:

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

### 5. Ejecutar migraciones

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```
### 6. Iniciar servidor de Desarrollo
Iniciar todos los servicios de Docker
```bash
./vendor/bin/sail up -d
```

Para detener los servicios:
```bash
./vendor/bin/sail up -d
```
# Comandos
## Gestión de Servicios Docker

### Iniciar todos los servicios de Docker
```bash
./vendor/bin/sail up

# Para ejecutar en segundo plano (detached mode)
./vendor/bin/sail up -d
```

### Detener todos los servicios
```bash
./vendor/bin/sail down
```

## Comandos de Desarrollo

### Ejecutar comandos Artisan
```bash
./vendor/bin/sail artisan [comando-artisan]

# Ejemplo
./vendor/bin/sail artisan migrate
```

### Ejecutar comandos Composer
```bash
./vendor/bin/sail composer [comando-composer]

# Ejemplo
./vendor/bin/sail composer require laravel/sanctum
```

### Ejecutar comandos npm/Yarn
```bash
./vendor/bin/sail npm [comando-npm]

# Ejemplos
./vendor/bin/sail npm run dev
./vendor/bin/sail yarn run dev
```

### Ejecutar comandos PHP
```bash
./vendor/bin/sail php [comando-php]

# Ejemplo
./vendor/bin/sail php --version
```

## Acceso a Contenedores

### Conectarse a la shell del contenedor de la aplicación
```bash
./vendor/bin/sail bash
```

### Conectarse a la shell del contenedor de base de datos
```bash
# Para MySQL
./vendor/bin/sail mysql

# Para PostgreSQL
./vendor/bin/sail psql
```

## Monitoreo y Logs

### Ver los logs de los contenedores
```bash
./vendor/bin/sail logs -f
```
