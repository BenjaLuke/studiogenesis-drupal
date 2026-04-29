# Contenido de prueba

Este documento recoge el contenido inicial creado desde la administracion de Drupal para validar el modelo de datos.

## Noticias

| Titulo | Categoria | Fecha noticia | Autor |
| --- | --- | --- | --- |
| Studiogenesis lanza una nueva linea de servicios web | Empresa | 2026-04-29 | Equipo Studiogenesis |
| Drupal 11 como base para proyectos corporativos | Tecnologia | 2026-04-22 | Equipo tecnico |
| Nuevas tendencias en experiencias digitales | Actualidad | 2026-04-15 | Redaccion |

Objetivo de estas noticias:

- Probar listados de noticias.
- Probar filtros por titulo, categoria y fecha.
- Validar que cada noticia abre una ficha completa.
- Dejar preparado contenido para las futuras vistas publicas.

## Productos

| Nombre | Categoria | Tarifa |
| --- | --- | --- |
| Portal corporativo Drupal | Software | 2490.00 |
| Auditoria web tecnica | Consultoria | 890.00 |
| Formacion Drupal para equipos | Formacion | 1250.50 |

Objetivo de estos productos:

- Probar listados de productos.
- Probar filtros por nombre y categoria.
- Validar fichas de producto con descripcion, foto y tarifa.
- Comprobar que la biblioteca de medios funciona con imagenes.

## Medios

Estado verificado:

- 3 productos tienen foto asociada.
- Existen elementos multimedia de tipo imagen.
- Existen elementos multimedia de tipo documento.

## Nota sobre exportacion SQL

Los terminos de taxonomia, noticias, productos y medios son contenido almacenado en base de datos. Por eso se ha generado una exportacion SQL preliminar en:

```text
database/studiogenesis_drupal_preliminar.sql
```

Ese archivo queda fuera de Git porque el repositorio es publico. Se incluira en la entrega final si procede.
