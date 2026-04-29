# Newsletter

Este documento recoge las decisiones tecnicas de la newsletter para facilitar el informe final.

## Datos de suscripcion

La newsletter se guardara en una tabla propia llamada `studiogenesis_newsletter_subscription`.

Campos previstos:

- `id`: identificador interno automatico.
- `nombre`: nombre de la persona suscrita.
- `apellidos`: apellidos de la persona suscrita.
- `email`: correo electronico, unico para evitar duplicados.
- `interes`: tema elegido en el formulario (`noticias`, `productos` o `ambos`).
- `acepta_privacidad`: confirmacion de aceptacion de privacidad.
- `activo`: estado de la suscripcion.
- `created`: fecha de alta.
- `updated`: fecha de ultima modificacion.

## Modulo personalizado

El modulo se llama `studiogenesis_newsletter` y vive en:

`web/modules/custom/studiogenesis_newsletter`

Su objetivo es resolver el requisito de newsletter con codigo propio, manteniendo separada la logica de suscripciones del contenido normal de Drupal.

## Formulario publico

El formulario publico queda disponible en `/newsletter`.

Funcionamiento previsto:

- Muestra nombre, apellidos, email, interes y aceptacion de privacidad.
- Valida que el email tenga formato correcto.
- Impide registrar dos suscripciones con el mismo email.
- Guarda la suscripcion activa en la tabla propia del modulo.
- Anade el enlace `Newsletter` al menu principal del sitio.

## Listado de administracion

El listado de administracion queda disponible en `/admin/content/newsletter`.

Funcionamiento previsto:

- Usa el permiso propio `administer studiogenesis newsletter`.
- Lee las suscripciones desde la tabla `studiogenesis_newsletter_subscription`.
- Muestra ID, nombre, apellidos, email, interes, estado y fecha de alta.
- Ordena las suscripciones por fecha de alta descendente.
- Anade un enlace dentro de la seccion administrativa de contenido.

## Edicion y eliminacion

Cada fila del listado administrativo incluye operaciones para gestionar el registro.

Funcionamiento previsto:

- `Editar` abre `/admin/content/newsletter/{subscription}/edit`.
- La edicion permite modificar nombre, apellidos, email, interes y estado activo.
- La validacion impide asignar un email que ya pertenezca a otra suscripcion.
- `Eliminar` abre `/admin/content/newsletter/{subscription}/delete`.
- El borrado exige confirmacion antes de eliminar definitivamente el registro.

## Exportacion CSV

La exportacion queda disponible en `/admin/content/newsletter/export`.

Funcionamiento previsto:

- Usa el permiso propio `administer studiogenesis newsletter`.
- Devuelve una respuesta HTTP de descarga, no una pagina HTML.
- Exporta ID, nombre, apellidos, email, interes, privacidad, estado y fechas.
- Usa separador `;` para facilitar la apertura del CSV en Excel con configuracion espanola.
- El enlace de acceso se anadio desde la interfaz de Drupal para practicar la gestion de menus.
