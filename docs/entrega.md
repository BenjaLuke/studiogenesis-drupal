# Entrega del proyecto

Documento de apoyo para revisar la prueba tecnica Drupal de Studiogenesis.

## Destinatario

Proyecto preparado para revision por Jose Antonio Parra.

## Resumen

El proyecto implementa una web Drupal funcional con noticias, productos, newsletter, buscador, filtros publicos, administracion de suscriptores, exportacion CSV y tema visual personalizado basado en Bootstrap 4.

## Entorno

- Drupal: 11.3.8.
- PHP: 8.3.30.
- MySQL: 8.4.
- Drush: 13.7.2.
- Tema publico: `studiogenesis_theme`.
- Tema de administracion: Claro.
- Entorno local: Docker Compose.

## URLs principales

- Inicio: `http://localhost:8081/`
- Noticias: `http://localhost:8081/noticias`
- Productos: `http://localhost:8081/productos`
- Newsletter publica: `http://localhost:8081/newsletter`
- Administracion newsletter: `http://localhost:8081/admin/content/newsletter`
- Exportacion CSV newsletter: `http://localhost:8081/admin/content/newsletter/export`
- phpMyAdmin: `http://localhost:8082`

## Acceso local

- Usuario administrador local: `admin`.
- La contrasena se debe facilitar por canal privado, no en el repositorio publico.

Credenciales de base de datos local:

- Base de datos: `studiogenesis_drupal`
- Usuario: `drupal`
- Password: `drupal`
- Root password: `root`
- Host desde Docker: `db`
- Host desde Windows: `localhost`
- Puerto desde Windows: `3308`

## Funcionalidades implementadas

### Noticias

- Tipo de contenido `Noticia`.
- Campos: fecha, subtitulo, categoria, cuerpo, autor y ficheros relacionados.
- Vocabulario de categorias de noticias.
- Listado publico `/noticias`.
- Filtro publico por categoria.
- Buscador publico por titulo.
- Presentacion publica revisada para mostrar archivos correctamente.

### Productos

- Tipo de contenido `Producto`.
- Campos: categoria, descripcion corta, descripcion larga, foto y tarifa.
- Vocabulario de categorias de productos.
- Listado publico `/productos`.
- Filtro publico por categoria.
- Buscador publico por titulo.
- Presentacion publica revisada para mostrar imagenes de producto correctamente.

### Newsletter

- Modulo personalizado `studiogenesis_newsletter`.
- Formulario publico `/newsletter`.
- Tabla propia `studiogenesis_newsletter_subscription`.
- Validacion de email y control de duplicados.
- Administracion de suscriptores.
- Edicion, eliminacion y estado activo/inactivo.
- Exportacion CSV con separador `;`.

### Tema visual

- Tema personalizado `studiogenesis_theme`.
- Bootstrap 4.6.2 incluido localmente.
- Logo real de Studiogenesis.
- Paleta visual basada en el azul del logo.
- Estilos para cabecera, menus, formularios, tablas, filtros y contenido.
- Revision responsive basica en noticias, productos y newsletter.

## Exportaciones

Configuracion Drupal:

- Carpeta versionada: `config/sync`.

Base de datos:

- SQL final local: `database/studiogenesis_drupal_final_2026-04-29.sql`.
- La carpeta `database/` esta ignorada por Git.
- El SQL debe incluirse en el ZIP final de entrega si se pide base de datos completa.

## Como levantar el proyecto

Desde la carpeta raiz:

```powershell
docker compose up -d --build
```

Instalar dependencias PHP si faltan:

```powershell
docker compose exec app composer install
```

Comprobar Drupal:

```powershell
docker compose exec app vendor/bin/drush -r web status
```

Limpiar caches:

```powershell
docker compose exec app vendor/bin/drush -r web cache:rebuild
```

## Capturas recomendadas para el informe

- Pagina de inicio.
- Listado de noticias con filtros.
- Detalle de una noticia con ficheros relacionados.
- Listado de productos con filtros.
- Detalle de un producto con foto visible.
- Formulario publico de newsletter.
- Listado administrativo de newsletter.
- Exportacion CSV abierta.
- Pantalla de modulos mostrando el modulo personalizado activo.
- Apariencia mostrando el tema `Studiogenesis Theme`.

## Notas finales

- El repositorio publico no debe contener la exportacion SQL ni contrasenas en texto plano.
- El SQL final y las credenciales de administracion deben entregarse por un canal privado o dentro del paquete final acordado.
- El proyecto se ha construido de forma incremental y queda documentado en `docs/progreso.md`.
