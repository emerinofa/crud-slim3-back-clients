# Proyecto CRUD con SLIM 3 (PHP)

Este proyecto es el backend desarrollado en PHP con SLIM 3

## Requisitos previos

Asegúrate de tener instalado lo siguiente:

- PHP: Versión 5.5 o posterior.
- Composer: Gestor de paquetes de PHP.
- Git: Para clonar el repositorio.
- XAMMP 

## Instrucciones de instalación
#### 1. Clonar el proyecto
Clona el repositorio:
`git clonehttps://github.com/emerinofa/crud-slim3-back-clients.git`
#### 2. Instala las dependencias
`composer install`
#### 3. Inicia el servidor local
- Colocate en tu directorio por ejemplo: `/c/xampp/htdocs/ejemploSlim/public`
- Puedes utilizar el servidor web incorporado de PHP: `php -S localhost:8000`

#### 4. Crea la base de datos 
- Crea una base de datos de nombre bd_customers con los campos:
- id (autoincremental INT(11))
- lastName (varchar(45))
- age (INT)
- birthdate (DATE)
- dni (VARCHAR(8))
- estado (TINYINT(4))


#### 4. Accede a la aplicacion
- Abre tu navegador y visita `http://localhost:8080/GET/listclients`

## Autor
- MERINO FARFAN ELVIS HERNAN