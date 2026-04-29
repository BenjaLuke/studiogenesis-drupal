# Progreso del proyecto

Este documento resume las decisiones y avances principales del proyecto. Sirve como apoyo para el informe final y para recordar que se ha trabajado de forma incremental.

## Punto 1 - Mapa del proyecto

- Se reviso la prueba tecnica de Studiogenesis.
- Se identificaron requisitos obligatorios: Drupal, MySQL, Bootstrap 4, noticias, productos, newsletter, filtros, buscador y documentacion.
- Se decidio construir una version funcional y despues revisarla para mejorar calidad, diseno y entrega.

## Punto 2 - Preparacion del entorno

- Se comprobo Windows 11, Docker Desktop, Docker Compose, Git, WSL2 y VS Code.
- Se decidio trabajar con Docker Desktop usando WSL2 por debajo.
- Se detecto otro proyecto Laravel activo, por lo que se reservaron puertos distintos para Drupal.

## Punto 3 - Estructura inicial y Git

- Se creo la carpeta `estudiogenesis-drupal`.
- Se inicializo Git en la rama `main`.
- Se creo una estructura base con carpetas para documentacion, base de datos y Docker.

## Punto 4 - Docker Compose

- Se creo un entorno con PHP 8.3 + Apache, MySQL 8.4 y phpMyAdmin.
- Drupal queda disponible en `http://localhost:8081`.
- phpMyAdmin queda disponible en `http://localhost:8082`.
- MySQL queda expuesto en `localhost:3308`.

## Punto 4.5 - GitHub

- Se creo el repositorio publico `BenjaLuke/studiogenesis-drupal`.
- Se conecto el remoto `origin`.
- Se subieron los commits iniciales.

## Punto 5 - Instalacion limpia de Drupal

- Se instalo Drupal 11 mediante Composer.
- Se anadio Drush como herramienta de desarrollo.
- Se conecto Drupal con MySQL dentro de Docker.
- Se verifico que Drupal carga correctamente.

## Punto 6 - Configuracion basica

- Se configuro el nombre del sitio, slogan, pais y zona horaria.
- Se activo el idioma espanol y se establecio como idioma predeterminado.
- Se importaron traducciones de interfaz.
- Se revisaron modulos base necesarios para el proyecto.
- Se activaron Media y Media Library para gestionar imagenes y ficheros de forma mas comoda.
- Se resolvio un problema de permisos en `web/sites/default/files` que impedia importar traducciones.

## Punto 7 - Modelo de datos

- Se crearon los vocabularios `Categorías de noticias` y `Categorías de productos`.
- Se anadieron terminos iniciales para probar filtros y listados.
- Se creo el tipo de contenido `Noticia` con fecha, subtitulo, categoria, cuerpo, autor y ficheros relacionados.
- Se creo el tipo de contenido `Producto` con categoria, descripcion corta, descripcion larga, foto y tarifa.
- Se reviso la presentacion del formulario para ordenar los campos de edicion.
- Se reviso la presentacion publica inicial de ambos tipos de contenido.
- Se exporto la configuracion Drupal a `config/sync` para versionar el modelo en Git.

## Punto 8 - Contenido de prueba

- Se crearon 3 noticias publicadas con categorias y fechas distintas.
- Se crearon 3 productos publicados con categorias, descripciones, fotos y tarifas.
- Se comprobo que existen 3 productos con foto asociada.
- Se genero una exportacion SQL preliminar local en `database/studiogenesis_drupal_preliminar.sql`.
- La exportacion SQL queda ignorada por Git porque el repositorio de GitHub es publico.
