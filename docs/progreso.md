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

## Punto 9 - Listados publicos con Views

- Se creo la vista `Noticias` en la ruta `/noticias`.
- La vista de noticias muestra titulo, fecha de noticia, subtitulo, categoria y autor.
- Las noticias se ordenan por `Fecha noticia` en orden descendente.
- Se anadio un filtro expuesto por categoria para que el visitante pueda filtrar noticias.
- Se anadio un buscador expuesto por titulo dentro del listado de noticias.
- Se creo la vista `Productos` en la ruta `/productos`.
- La vista de productos muestra titulo, categoria, descripcion corta y tarifa.
- Los productos se ordenan alfabeticamente por titulo.
- Se anadio un filtro expuesto por categoria para que el visitante pueda filtrar productos.
- Se anadio un buscador expuesto por titulo dentro del listado de productos.
- Se anadieron enlaces a `Noticias` y `Productos` en el menu principal.
- Se reviso el buscador global del sitio y se ejecuto cron para indexar el contenido.
- Se exporto la configuracion de las vistas a `config/sync`.

## Punto 10 - Newsletter

- Se creo el modulo personalizado `studiogenesis_newsletter`.
- Se definio una tabla propia `studiogenesis_newsletter_subscription` para guardar suscripciones.
- La tabla guarda nombre, apellidos, email, interes, aceptacion de privacidad, estado y fechas.
- Se creo el formulario publico `/newsletter`.
- El formulario valida email, exige aceptacion de privacidad e impide emails duplicados.
- Se creo el listado administrativo `/admin/content/newsletter`.
- El listado muestra suscriptores con estado, interes y fecha de alta.
- Se anadieron operaciones administrativas para editar y eliminar suscriptores.
- La edicion impide usar un email que ya pertenezca a otra suscripcion.
- El borrado exige confirmacion antes de eliminar el registro.
- Se creo la exportacion CSV en `/admin/content/newsletter/export`.
- El CSV usa separador `;` para facilitar la apertura en Excel.
- Se reviso el permiso propio `Administrar newsletter de Studiogenesis`.
- Se exporto la configuracion para registrar que el modulo queda habilitado.

## Punto 11 - Tema visual y Bootstrap 4

- Se creo el tema personalizado `studiogenesis_theme`.
- El tema usa `stable9` como tema base de Drupal.
- Se incluyo Bootstrap 4.6.2 localmente dentro del tema para no depender de CDN.
- Se creo una plantilla principal `page.html.twig` con cabecera, menu, contenido, laterales y pie.
- Se creo una hoja de estilos propia para cabecera, menus, formularios, tablas y filtros de Views.
- Se activo el tema `Studiogenesis Theme` como tema predeterminado.
- Se recolocaron bloques del tema desde la interfaz de Drupal.
- Se sustituyo el logo provisional por el logo real de Studiogenesis.
- Se ajusto la paleta visual al azul del logo.
- Se limpiaron etiquetas tecnicas de los filtros expuestos en las vistas de noticias y productos.
- Se reviso el comportamiento responsive basico en noticias, productos y newsletter.
- Se exporto la configuracion del tema, bloques y vistas a `config/sync`.
