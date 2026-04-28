# Studiogenesis Drupal

Proyecto de prueba tecnica para desarrollador web basado en Drupal.

Repositorio publico: <https://github.com/BenjaLuke/studiogenesis-drupal>

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
recipes/   Recetas Drupal generadas por la plantilla oficial.
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

## Drupal y Composer

Drupal se instala mediante Composer usando la plantilla oficial `drupal/recommended-project`.
Esto permite reproducir dependencias y mantener el core separado de los archivos publicos.

Versiones verificadas en el entorno local:

- Drupal: `11.3.8`
- PHP: `8.3.30`
- Composer: `2.9.7`
- Drush: `13.7.2`

Comandos utiles dentro del contenedor:

```powershell
docker compose exec app composer install
docker compose exec app vendor/bin/drush status
docker compose exec app vendor/bin/drush cache:rebuild
docker compose exec app vendor/bin/drush config:export --destination=../config/sync -y
```

Instalacion local de Drupal con Drush:

```powershell
docker compose exec --user www-data app vendor/bin/drush site:install standard --db-url=mysql://drupal:drupal@db:3306/studiogenesis_drupal --site-name="Studiogenesis Drupal" --account-name=admin --account-pass="<contrasena-local>" --account-mail=admin@example.com -y
```

El archivo `web/sites/default/settings.php` y la carpeta `web/sites/default/files/` se generan en local y no se versionan porque contienen configuracion del entorno y archivos generados por Drupal.

La configuracion estructural de Drupal se exporta en `config/sync`. Ahi quedan versionados vocabularios, tipos de contenido, campos, formularios, presentaciones y modulos activos.

Si Drupal no puede importar traducciones o guardar archivos generados, revisar permisos de la carpeta publica de archivos:

```powershell
docker compose exec app mkdir -p web/sites/default/files/translations
docker compose exec app chown -R www-data:www-data web/sites/default/files
```

## Configuracion basica realizada

- Idioma predeterminado: espanol.
- Pais predeterminado: Espana.
- Zona horaria: `Europe/Madrid`.
- Nombre del sitio: `Studiogenesis Drupal`.
- Slogan: `Noticias, productos y newsletter`.
- Tema publico provisional: Olivero.
- Tema de administracion: Claro.
- Modulos base revisados: campos, ficheros, imagenes, taxonomias, buscador, vistas y menus.
- Modulos multimedia activados: Media y Media Library.

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

Fase actual: modelo de datos de noticias y productos creado y exportado a configuracion.
