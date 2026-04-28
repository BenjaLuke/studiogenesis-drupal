# Studiogenesis Drupal

Proyecto de prueba tecnica para desarrollador web basado en Drupal.

## Objetivo

Crear una web Drupal con gestion de noticias, productos y newsletter, incluyendo parte publica, administracion interna, tema personalizado con Bootstrap 4 y documentacion de entrega.

## Alcance inicial

- Drupal minimo 9, con preferencia por Drupal 11 si no aparece ningun bloqueo tecnico.
- Base de datos MySQL o MariaDB.
- Entorno local reproducible con Docker Compose.
- Tema personalizado con Bootstrap 4.
- Tipos de contenido para noticias y productos.
- Modulo propio para alta, gestion y exportacion de newsletter.
- Documentacion final con capturas, accesos e instrucciones.

## Estructura

```text
docs/      Documentacion, capturas e informe final.
database/  Exportaciones de base de datos para la entrega.
docker/    Configuracion auxiliar del entorno Docker.
web/       Document root publico de Drupal.
```

## Entorno Docker

El proyecto usa Docker Compose para levantar un entorno local reproducible.

Servicios principales:

- `app`: PHP 8.3 + Apache preparado para Drupal.
- `db`: MySQL 8.4 para la base de datos del proyecto.
- `phpmyadmin`: interfaz web para revisar la base de datos.

Puertos por defecto:

- Drupal: <http://localhost:8081>
- phpMyAdmin: <http://localhost:8082>
- MySQL: `localhost:3308`

Comandos utiles:

```powershell
docker compose up -d --build
docker compose ps
docker compose logs app
docker compose down
```

Credenciales locales de base de datos:

```text
Host Drupal: db
Host desde Windows: localhost
Base de datos: studiogenesis_drupal
Usuario: drupal
Password: drupal
Root password: root
```

## Estado

Fase actual: entorno Docker inicial preparado y verificado.
