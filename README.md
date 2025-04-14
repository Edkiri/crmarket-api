# Basic Laravel Docker Starter

Este proyecto proporciona una configuración básica para iniciar un proyecto Laravel utilizando Docker. La configuración incluye Apache como servidor web y MySQL como base de datos.

## Requisitos previos

- Docker
- Docker Compose

## Instalación

Sigue estos pasos para configurar el proyecto:

1. Clona este repositorio en tu máquina local.

2. Navega hasta el directorio del proyecto.

3. Copia el archivo de configuración de entorno:

   ```
   cp src/.env.example src/.env
   ```

4. Inicia los contenedores Docker:

   ```
   docker compose up
   ```

5. Cambia los permisos del directorio `src`:

   ```
   sudo chown 1000:1000 src/ -R
   ```

6. Accede al contenedor de la aplicación Laravel:

   ```
   docker exec -it laravel_app bash
   ```

7. Ejecuta las migraciones de Laravel:

   ```
   php artisan migrate
   ```

## Uso

Una vez completados los pasos de instalación, tu aplicación Laravel debería estar ejecutándose y accesible a través de `http://localhost` (o la dirección IP y puerto que hayas configurado).

## Estructura del proyecto

- `src/`: Contiene el código fuente de la aplicación Laravel.
- `docker-compose.yml`: Define los servicios, redes y volúmenes para Docker.